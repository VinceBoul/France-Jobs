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
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JobCronUpdater
{
    public const JSON_URL = "https://www.fraisetlocal.fr/api/v2/catalog/datasets/flux-toutes-plateformes/exports/geojson?rows=10&select=nom%2Cadresse_app%2Cgeolocalisation%2Ccategorie%2Cnom_de_la_plateforme%2Cfamilles_des_produits&record_metas=true";

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

                dump($job->url_sur_la_plateforme_partenaire);
                $this->crawlJobPage(null, $job->url_sur_la_plateforme_partenaire);

                $existingJob = new Job();
                if ($job->geolocalisation){
                    $lat = $job->geolocalisation->lat;
                    $lon = $job->geolocalisation->lon;
                }else {
                    $lat = null;
                    $lon = null;
                }
                $existingJob->initJob(
                    $job->nom,$job->reg_name,$lat,$lon, $job->com_name,
                    $job->raison_sociale, $job->code_postal, $job->code_insee_commune, $job->dep_name, $job->dep_code,
                    $job->adresse, $apiJob->properties->recordid
                );


                $this->em->persist($existingJob);
               // $this->em->flush();

                $produitsCategories = $job->familles_des_produits;

                if ($produitsCategories){

                  //  $this->updateJobCategories($existingJob, $produitsCategories);
                }
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

    private function crawlJobPage($job, $jobPageUrl){

//        $jobPageUrl = "https://www.bienvenue-a-la-ferme.com/bretagne/finistere/lampaul-ploudalmezeau/ferme/gaec-gwel-ar-mor/139775";

        // /thumb/generate/800x600/93804679_708690823207659_6342845386775855104_n.jpg
        // https://www.bienvenue-a-la-ferme.com/thumb/generate/800x600/93804679_708690823207659_6342845386775855104_n.jpg
        $response = $this->client->request('GET', $jobPageUrl);
        $siteInfo = $response->getInfo();
        $html = $response->getContent();

        $crawler = new Crawler($html);

        if (strpos($jobPageUrl, '-a-la-ferme.com') !== false){

            // Types de produits
            $nodeValues = $crawler->filter('#content_decouvrir_produits > p')->each(function (Crawler $node, $i) {
                return $node->text();
            });
            if (!empty($nodeValues)){
                dump('NodeValues', $nodeValues);
            }

            // https://www.bretagnealaferme.com/img/thumbnails/416_254/93804679_708690823207659_6342845386775855104_n.jpg?1621070167
            // https://www.bretagnealaferme.com/thumb/generate/800x600/93804679_708690823207659_6342845386775855104_n.jpg

            // Images
            $imgsLink = $crawler->filter('.slides li')->each(function (Crawler $node, $i) {
                return $node->filter('a')->eq(0)->attr('href');
            });
            $imgsTitle = $crawler->filter('.slides li')->each(function (Crawler $node, $i) {
                return $node->filter('a')->eq(0)->attr('title');
            });
            dump($imgsLink);

            if (empty($imgsLink)){
                $uniqImage = $crawler->filter('#prettyPhoto-button')->attr('href');
                dump($uniqImage);
            }else{
                $i = 0;
                foreach ($imgsLink as $img){
                    $this->createImage($img, $job, $imgsTitle);
                }
            }

            $coordonnees = $crawler->filter('.coordonnees > p')->eq(0)->html();
            //dd($coordonnees);
            $infoPratiques = $crawler->filter('#content_acheter_produits p');

        }

        return $job;
    }

    private function createImage($img, Job $job){
        $newImg = new FleImage();

        $img = "https://www.bienvenue-a-la-ferme.com".$img;
// Use basename() function to return the base name of file
        $file_name = basename($img);
       // dump();

        //$image = file_get_contents('http://www.affiliatewindow.com/logos/1961/logo.gif');

        $filePath = $this->kernel->getProjectDir() . '/public'.$this->params->get('app.path.job_images').'/'.$file_name, file_get_contents($img);
        $f = new File($filePath);

        $newImg->setImageFile($f);

        //dd($this->kernel->getProjectDir() . '/public'.$this->params->get('app.path.job_images').$file_name);
        //file_put_contents();

    }
}
