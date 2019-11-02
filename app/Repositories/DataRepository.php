<?php
namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;

use App\Repositories\BilanRepository;
use App\Repositories\StatRepository;
use App\Repositories\AdminRepository;
use App\Repositories\VendeurRepository;
use App\Repositories\MagasinierRepository;
use App\Repositories\PorteurRepository;
use App\Repositories\FournisseurRepository;
use App\Repositories\CategorieRepository;
use App\Repositories\ProduitRepository;
use App\Repositories\InventaireRepository;
use App\Repositories\EntreeRepository;
use App\Repositories\VenteRepository;

use App\Repositories\NotificationRepository;

class DataRepository
{

	protected $admin;
	protected $notification;
    protected $stat;
    protected $bilan;
    protected $vendeur;
    protected $magasinier;
    protected $porteur;
    protected $fournisseur;
    protected $categorie;
    protected $produit;
    protected $vente;
    protected $entree;
    protected $inventaire;

    public function __construct(
        AdminRepository $admin,
        StatRepository $stat,
        Notification $notification,
        BilanRepository $bilan,
        VendeurRepository $vendeur,
        MagasinierRepository $magasinier,
        PorteurRepository $porteur,
        FournisseurRepository $fournisseur,
        CategorieRepository $categorie,
        ProduitRepository $produit,
        VenteRepository $vente,
        EntreeRepository $entree,
        InventaireRepository $inventaire
		)
	{
		$this->admin = $admin;
		$this->notification = $notification;
        $this->stat = $stat;
        $this->bilan = $bilan;
        $this->vendeur = $vendeur;
        $this->magasinier = $magasinier;
        $this->porteur = $porteur;
        $this->fournisseur = $fournisseur;
        $this->categorie = $categorie;
        $this->produit = $produit;
        $this->entree = $entree;
        $this->vente = $vente;
        $this->inventaire = $inventaire;
    }
    
    public function check($fonction, $userId, $notificationId)
	{
        if ($fonction === "admin"){
            $objs = $this->notification->where('id', '>', $notificationId)->orderBy('id', 'desc')->get();
            $response = [];
            if (count($objs, 0)>0){
                array_push($response, (object)[
                    'notification'=>$objs
                ]);
                $today = date('Y-m-d');
                $stats= $this->stat->getSell($today);
                array_push($response, (object)[
                    'stat'=>$stats
                ]);
                foreach($objs as $obj){
                    switch($obj->type){
                        case 'admin':
                            $data = $this->admin->getAll();
                            array_push($response, (object)[
                                'admin'=>$data
                            ]);
                        break;
                        case 'vendeur':
                            $data = $this->vendeur->getAll();
                            array_push($response, (object)[
                                'vendeur'=>$data
                            ]);
                        break;
                        case 'magasinier':
                            $data = $this->magasinier->getAll();
                            array_push($response, (object)[
                                'magasinier'=>$data
                            ]);
                        break;
                        case 'porteur':
                            $data = $this->porteur->getAll();
                            array_push($response, (object)[
                                'porteur'=>$data
                            ]);
                        break;
                        case 'fournisseur':
                            $data = $this->fournisseur->getAll();
                            array_push($response, (object)[
                                'fournisseur'=>$data
                            ]);
                        break;
                        case 'categorie':
                            $data = $this->categorie->getAll();
                            array_push($response, (object)[
                                'categorie'=>$data
                            ]);
                        break;
                        case 'produit':
                            $data = $this->produit->getAll();
                            array_push($response, (object)[
                                'produit'=>$data
                            ]);
                        break;
                        case 'vente':
                            $today=date('Y-m-d');
                            $data = $this->vente->getAll($today);
                            array_push($response, (object)[
                                'vente'=>$data
                            ]);
                        break;
                        case 'entree':
                            $year = date('Y');
                            $data = $this->entree->getAll($year);
                            array_push($response, (object)[
                                'entree'=>$data
                            ]);
                        break;
                        case 'inventaire':
                            $year = date('Y');
                            $data = $this->inventaire->getAll($year);
                            array_push($response, (object)[
                                'inventaire'=>$data
                            ]);
                        break;
                    }
                }
            }
            return $response;
           
        } elseif ($fonction ==="vendeur"){

        } elseif ($fonction === "magasinier"){

        }
    }

    public function getAll($fonction, $id)
	{
        if ($fonction === "admin"){
            $today=date('Y-m-d');
            $year = date('Y');

            $objs = DB::table('notifications')
            ->whereDate('created_at', $today)
            ->orderBy('id', 'desc')
            ->get();
            $admin = $this->admin->getAll();
            $vendeur = $this->vendeur->getAll();
            $magasinier = $this->magasinier->getAll();
            $porteur = $this->porteur->getAll();
            $fournisseur = $this->fournisseur->getAll();
            $categorie = $this->categorie->getAll();
            $produit = $this->produit->getAll();
            $vente = $this->vente->getAll($today);
            $entree = $this->entree->getAll($year);
            $inventaire = $this->inventaire->getAll($year);
            $data = array(
                'notification' =>$objs,
                'admin'=>$admin,
                'vendeur'=>$vendeur,
                'magasinier'=>$magasinier,
                'porteur'=>$porteur,
                'fournisseur'=>$fournisseur,
                'categorie'=>$categorie,
                'produit'=>$produit,
                'vente'=>$vente,
                'entree'=>$entree,
                'inventaire'=>$inventaire
            );
            return $data;   
                      
                        
        } elseif ($fonction ==="vendeur"){

        } elseif ($fonction === "magasinier"){

        }
    }

	private function get($fonction, $userId, $notificationId)
	{
		if ($fonction === "admin"){

        } elseif ($fonction ==="vendeur"){

        } elseif ($fonction === "magasinier"){

        }
	}

	
}