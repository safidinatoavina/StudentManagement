<?php

namespace App\Service\Cache;

use App\Jobs\refreshCacheJob;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Etudiant\EtudiantController;
use App\Http\Controllers\data_faculte\DataFaculteController;


/**
 *  Les cache utiliser dans l'appliction sera ici
 */

class SystemeCache
{

    private $ACTIVE_CACHE=false;
    private $USE_QUEUE_CACHE=false;

    /// les cache qui n'est pas lister ici n'est pas accepter
    private $liste_caches=[
        'liste_etudiant_inscrit',
        'liste_admins',
        'liste_mentions',
        'liste_grades',
        'liste_parcours',
        'liste_annees',
        'liste_ues',
        'liste_matieres',
        'liste_tps'
    ];

    public function __construct($ACTIVE_CACHE='',$USE_QUEUE_CACHE=''){

        if($ACTIVE_CACHE===''&&$USE_QUEUE_CACHE==''){
            $this->ACTIVE_CACHE=!!env("ACTIVE_CACHE",false);
            $this->USE_QUEUE_CACHE=!!env("USE_QUEUE_CACHE",false);
        }else{
            $this->ACTIVE_CACHE=$ACTIVE_CACHE;
            $this->USE_QUEUE_CACHE=$USE_QUEUE_CACHE;
        }

    }

    /**
     *  LISTE DES DONNNE EN kebab case
     */

     public function liste_etudiant_inscrit(){

        $EtudiantController=new EtudiantController;
        if($this->ACTIVE_CACHE){
            return $this->getCache('liste_etudiant_inscrit');
        }
        else{
            return $EtudiantController->liste_etudiant_inscrit();
        }

     }

     public function liste_admins(){

        $AdminController=new AdminController;

        if($this->ACTIVE_CACHE){
            return $this->getCache('liste_admins');
        }
        else{
            return $AdminController->liste_admins();
        }
     }

     public function mentions(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){
            return $this->getCache('liste_mentions');
        }
        else{
            return $DataFaculteController->mentions();
        }

     }

    public function grades(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){
            return $this->getCache('liste_grades');
        }
        else{
            return $DataFaculteController->grades();
        }

     }


    public function parcours(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){
            return $this->getCache('liste_parcours');
        }
        else{
            return $DataFaculteController->parcours();
        }

     }


    public function annees(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){
            return $this->getCache('liste_annees');
        }
        else{
            return $DataFaculteController->annees();
        }

     }

    public function ues(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){
            return $this->getCache('liste_ues');
        }
        else{
            return $DataFaculteController->ues();
        }

     }

    public function matiers(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){
            return $this->getCache('liste_matieres');
        }
        else{
            return $DataFaculteController->matiers();
        }

     }

    public function tps(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){
            return $this->getCache('liste_tps');
        }
        else{
            return $DataFaculteController->tps();
        }

     }


     //______________CACHE MANIPULATION__________________

    private function validateManipulation(string $nom)
    {
        if(!\in_array($nom,$this->liste_caches))
            abort(500,"index cache(".$nom.") n'est pas dans liste_caches");
    }

    public function getListeCaches(){
        return $this->liste_caches;
    }

    public function setCache( string $nom,$data){

        $this->validateManipulation($nom);
        Cache::forever($nom, $data);
    }

    public function getCache(string $nom){
        $this->validateManipulation($nom);
        return Cache::get($nom);
    }

    public function forgetCache(string $nom){
        $this->validateManipulation($nom);
        Cache::forget($nom);
    }

    //----------------------REFRESH---------------------------------------

    public function refreshCacheStudent(){

        $EtudiantController=new EtudiantController;
        
        if($this->ACTIVE_CACHE){

            if($this->USE_QUEUE_CACHE){
                refreshCacheJob::dispatch('refreshCacheStudent');
            }
            else{

                $this->forgetCache('liste_etudiant_inscrit');
                
                $listes = $EtudiantController->liste_etudiant_inscrit();
            
                $this->setCache('liste_etudiant_inscrit',$listes);

            }
        }

    }

    public function refreshCacheAdmin(){
        
        $AdminController=new AdminController;

        if($this->ACTIVE_CACHE){

            if($this->USE_QUEUE_CACHE){

                refreshCacheJob::dispatch('refreshCacheAdmin');

            }else{

                $this->forgetCache('liste_admins');
                $listes = $AdminController->liste_admins();
                $this->setCache('liste_admins', $listes);

            }

        }

    }

    public function refreshCacheMentions(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){

            if($this->USE_QUEUE_CACHE){

                refreshCacheJob::dispatch('refreshCacheMentions');

            }else{

                $this->forgetCache('liste_mentions');
                $listes = $DataFaculteController->mentions();
                $this->setCache('liste_mentions', $listes);

            }

        }

    }

    public function refreshCacheGrades(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){

            if($this->USE_QUEUE_CACHE){

                refreshCacheJob::dispatch('refreshCacheGrades');

            }else{

                $this->forgetCache('liste_grades');
                $listes = $DataFaculteController->grades();
                $this->setCache('liste_grades', $listes);

            }

        }

    }

    public function refreshCacheParcours(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){

            if($this->USE_QUEUE_CACHE){

                refreshCacheJob::dispatch('refreshCacheParcours');

            }else{

                $this->forgetCache('liste_parcours');
                $listes = $DataFaculteController->parcours();
                $this->setCache('liste_parcours', $listes);

            }

        }

    }


    public function refreshCacheAnnees(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){

            if($this->USE_QUEUE_CACHE){

                refreshCacheJob::dispatch('refreshCacheAnnees');

            }else{

                $this->forgetCache('liste_annees');
                $listes = $DataFaculteController->annees();
                $this->setCache('liste_annees', $listes);

            }

        }

    }

    public function refreshCacheUes(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){

            if($this->USE_QUEUE_CACHE){

                refreshCacheJob::dispatch('refreshCacheUes');

            }else{

                $this->forgetCache('liste_ues');
                $listes = $DataFaculteController->ues();
                $this->setCache('liste_ues', $listes);

            }

        }

    }


    public function refreshCacheMatieres(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){

            if($this->USE_QUEUE_CACHE){

                refreshCacheJob::dispatch('refreshCacheMatieres');

            }else{

                $this->forgetCache('liste_matieres');
                $listes = $DataFaculteController->matiers();
                $this->setCache('liste_matieres', $listes);

            }

        }

    }

    public function refreshCacheTps(){

        $DataFaculteController=new DataFaculteController;

        if($this->ACTIVE_CACHE){

            if($this->USE_QUEUE_CACHE){

                refreshCacheJob::dispatch('refreshCacheTps');

            }else{

                $this->forgetCache('liste_tps');
                $listes = $DataFaculteController->tps();
                $this->setCache('liste_tps', $listes);

            }

        }

    }




    /**
     *  ----------REFRESH ALL--------
     */

     public function refresh()
     {
        $this->refreshCacheStudent();
        $this->refreshCacheAdmin();
        $this->refreshCacheAnnees();
        $this->refreshCacheGrades();
        $this->refreshCacheMatieres();
        $this->refreshCacheMentions();
        $this->refreshCacheParcours();
        $this->refreshCacheUes();
        $this->refreshCacheTps();
     }
}

