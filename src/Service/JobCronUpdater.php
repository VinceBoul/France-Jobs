<?php


namespace App\Service;


use App\Entity\FleImage;
use App\Entity\Job;
use App\Entity\JobCategory;
use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JobCronUpdater
{
    public const JSON_URL = "https://www.fraisetlocal.fr/api/v2/catalog/datasets/flux-toutes-plateformes/exports/geojson?rows=15&select=nom%2Cadresse_app%2Cgeolocalisation%2Ccategorie%2Cnom_de_la_plateforme%2Cfamilles_des_produits&record_metas=true";

    public const RECORD_API_URL = "https://www.fraisetlocal.fr/api/v2/catalog/datasets/flux-toutes-plateformes/records/";

    private $kernel;

    private $em;

    private $client;

    private $params;

    public function __construct(Kernel $kernel, EntityManagerInterface $entityManager, HttpClientInterface $client, ParameterBagInterface $params)
    {
        $this->kernel = $kernel;
        $this->em = $entityManager;
        $this->client = $client;
        $this->params = $params;
    }

    public function updateJobs(&$output): void
    {
        $recorsdsIds = [];
        $allRecordsId = $this->em->getRepository(Job::class)->findRecordsId();
        foreach ($allRecordsId as $record){
            $recorsdsIds[] = $record['apiRecordId'];
        }

        $json = json_decode($this->client->request('GET', self::JSON_URL)->getContent());

        $apiJobs = $json->features;
        $progressBar = new ProgressBar($output, count($apiJobs));
        $progressBar->start();

        foreach ($apiJobs as $apiJob){

            $existingJob = $this->em->getRepository(Job::class)->findOneBy(['apiRecordId' => $apiJob->properties->recordid]);

            if (!$existingJob){
                $job = json_decode($this->client->request('GET', self::RECORD_API_URL.$apiJob->properties->recordid)->getContent());
                $job = $job->record->fields;

                $newJob = new Job();
                if ($job->geolocalisation){
                    $lat = $job->geolocalisation->lat;
                    $lon = $job->geolocalisation->lon;
                }else {
                    $lat = null;
                    $lon = null;
                }
                $newJob->initJob(
                    $job->nom,$job->reg_name,$lat,$lon, $job->com_name,
                    $job->raison_sociale, $job->code_postal, $job->code_insee_commune, $job->dep_name, $job->dep_code,
                    $job->adresse, $apiJob->properties->recordid
                );

                $newJob->setJobUrl($job->url_sur_la_plateforme_partenaire);
                $newJob = $this->crawlJobPage($newJob, $job->url_sur_la_plateforme_partenaire);

                $produitsCategories = $job->familles_des_produits;

                if ($produitsCategories){
                    //  $this->updateJobCategories($newJob, $produitsCategories);
                }

                $this->em->persist($newJob);
                $this->em->flush();
            }

            $progressBar->advance();
        }
        $this->em->flush();

        $progressBar->finish();
    }

    private function updateJobCategories(Job $existingJob, $produitsCategories){
        foreach ($produitsCategories as $category){
            $existingCategory = $this->em->getRepository(JobCategory::class)->findOneBy(['name' => $category]);
            if (!$existingCategory){
                $existingCategory = new JobCategory();
                $existingCategory->setName($category);
                $this->em->persist($existingCategory);
                $this->em->flush();
            }
            $existingJob->addCategory($existingCategory);
            $this->em->persist($existingJob);
            $this->em->flush();
        }
    }

    private function crawlJobPage(Job $job, $jobPageUrl){

        dump($jobPageUrl);

        try {
            $response = $this->client->request('GET', $jobPageUrl);

            if ( $response->getStatusCode() !== '404' && strpos($jobPageUrl, '-a-la-ferme.com') !== false){
                $html = $response->getContent();
                $crawler = new Crawler($html);

                // Types de produits
                $decouvrirProduits = $crawler->filter('#content_decouvrir_produits > p');
                if ($decouvrirProduits->count() > 0){

                    $nodeValues = $decouvrirProduits->each(function (Crawler $node, $i) {
                        return $node->text();
                    });

                    if (!empty($nodeValues)){
                        dump('NodeValues', $nodeValues);
                    }
                }

                // https://www.bretagnealaferme.com/img/thumbnails/416_254/93804679_708690823207659_6342845386775855104_n.jpg?1621070167
                // https://www.bretagnealaferme.com/thumb/generate/800x600/93804679_708690823207659_6342845386775855104_n.jpg

                // Description
                $presFerme = $crawler->filter('#description .padding .icones + p');
                if ($presFerme->count() > 0){
                    $description = $presFerme->html();
                    if(!empty($description)){
                        dump('Description', $description);
                        $job->setDescription($description);
                    }
                }

                // Images
                $imgsLink = $crawler->filter('.slides li')->each(function (Crawler $node, $i) {
                    return $node->filter('a')->eq(0)->attr('href');
                });
                $imgsTitle = $crawler->filter('.slides li')->each(function (Crawler $node, $i) {
                    return $node->filter('a')->eq(0)->attr('title');
                });

                if (empty($imgsLink)){
                    $photo = $crawler->filter('#prettyPhoto-button');
                    if ($photo->count() > 0){
                        $uniqImage = $photo->attr('href');
                        dump($uniqImage);
                    }
                }else{
                    $i = 0;
                    foreach ($imgsLink as $img){
                        $job = $this->createImage($img, $job, $imgsTitle[$i]);
                        $i++;
                    }
                }

                dump('coordonnees');
                $adress = $crawler->filter('.coordonnees > p')->eq(0);

                if ($adress->count() > 0){
                    $coordonnees = $adress->html();
                    dump($coordonnees);
                    if (!empty($coordonnees)){
                        $job->setCoordonnees($coordonnees);
                    }
                }
                dump('infoPratiques');

                $infoPratiques = $crawler->filter('#content_acheter_produits p');

                if ($infoPratiques->count() > 0){
                    dump($infoPratiques->html());
                    $job->setInfosPratique($infoPratiques->html());
                }

                $typeReglement = $crawler->filter('.types_reglement > p');

                if ($typeReglement->count() > 0){
                    dump($typeReglement->html());
                    $job->setTypeReglement($typeReglement->html());
                }

                $horaires = $crawler->filter('.horaires');

                if ($horaires->count() > 0){
                    dump($horaires->html());
                    $job->setHoraires($horaires->html());
                }

            }

            return $job;
        } catch (ExceptionInterface $e) {
            return $job;
        }
    }

    private function createImage($img, Job $job, $title){
        $newImg = new FleImage();

        $img = "https://www.bienvenue-a-la-ferme.com".$img;
        $file_name = basename($img);

        $filePath = $this->kernel->getProjectDir() . '/public'.$this->params->get('app.path.gallery_images').'/'.$file_name;
        file_put_contents($filePath, file_get_contents($img));
        $f = new File($filePath);

        $newImg->setImageFile($f);
        $newImg->setImage($file_name);
        $newImg->setDescription($title);
        $newImg->setTitle($title);

        $job->addCarouselImage($newImg);

        return $job;
    }
}
