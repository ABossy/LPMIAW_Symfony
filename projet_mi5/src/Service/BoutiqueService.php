<?php
namespace App\Service;
use Symfony\Component\HttpFoundation\RequestStack;

// Un service pour manipuler le contenu de la Boutique
//  qui est composée de catégories et de produits stockés "en dur"
class BoutiqueService {

    // renvoie toutes les catégories
    public function findAllCategories() {
        return $this->categories;
    }

    // renvoie la categorie dont id == $idCategorie
    public function findCategorieById(int $idCategorie) {
        $res = array_filter($this->categories,
                function ($c) use($idCategorie) {
            return $c["id"] == $idCategorie;
        });
        return (sizeof($res) === 1) ? $res[array_key_first($res)] : null;
    }

    // renvoie le produits dont id == $idProduit
    public function findProduitById(int $idProduit) {
        $res = array_filter($this->produits,
                function ($p) use($idProduit) {
            return $p["id"] == $idProduit;
        });
        return (sizeof($res) === 1) ? $res[array_key_first($res)] : null;
    }

    // renvoie tous les produits dont idCategorie == $idCategorie
    public function findProduitsByCategorie(int $idCategorie) {
        return array_filter($this->produits,
                function ($p) use($idCategorie) {
            return $p["idCategorie"] == $idCategorie;
        });
    }

    // renvoie tous les produits dont libelle ou texte contient $search
    public function findProduitsByLibelleOrTexte(string $search) {
        return array_filter($this->produits,
                function ($p) use ($search) {
                  return ($search=="" || mb_strpos(mb_strtolower($p["libelle"]." ".$p["texte"]), mb_strtolower($search)) !== false);
        });
    }

    // constructeur du service : injection des dépendances et tris
    public function __construct(RequestStack $requestStack) {
        // Injection du service RequestStack
        //  afin de pouvoir récupérer la "locale" dans la requête en cours
        $this->requestStack = $requestStack;
        // On trie le tableau des catégories selon la locale
        usort($this->categories, function ($c1, $c2) {
            return $this->compareSelonLocale($c1["libelle"], $c2["libelle"]);
        });
        // On trie le tableau des produits de chaque catégorie selon la locale
        usort($this->produits, function ($c1, $c2) {
            return $this->compareSelonLocale($c1["libelle"], $c2["libelle"]);
        });
    }

    ////////////////////////////////////////////////////////////////////////////

    private function compareSelonLocale(string $s1, $s2) {
        $collator=new \Collator($this->requestStack->getCurrentRequest()->getLocale());
        return collator_compare($collator, $s1, $s2);
    }

    private $requestStack; // Le service RequestStack qui sera injecté
    // Le catalogue de la boutique, codé en dur dans un tableau associatif
    private $categories = [
        [
            "id" => 1,
            "libelle" => "NIKE",
            "visuel" => "images/fruits.jpg",
            "texte" => "Just do it",
        ],
        [
            "id" => 3,
            "libelle" => "Adidas",
            "visuel" => "images/junk_food.jpg",
            "texte" => "impossible is nothing",
        ],
        [
            "id" => 2,
            "libelle" => "Reebok",
            "visuel" => "images/legumes.jpg",
            "texte" => "Be more human"],
    ];
    private $produits = [
        [
            "id" => 1,
            "idCategorie" => 1,
            "libelle" => "Nike React Element 55",
            "texte" => "Des touches de couleur sur toute la surface lui confèrent un look parfaitement équilibré et stylé.",
            "visuel" => "images/pommes.jpg",
            "prix" => 130
        ],
        [
            "id" => 2,
            "idCategorie" => 1,
            "libelle" => "Nike Air Force 1 '07",
            "texte" => "Cette silhouette iconique du basketball se pare d’une finition revisitée de ses éléments les plus remarquables : le cuir impeccable, les couleurs vives et l’éclat qui vous met naturellement en valeur",
            "visuel" => "images/poires.jpg",
            "prix" => 100
        ],
        [
            "id" => 3,
            "idCategorie" => 1,
            "libelle" => "Nike Air Max 98 LX ",
            "texte" => "Nike Air Max 98 LX arbore les lignes du modèle original inspirées des parois du Grand Canyon ",
            "visuel" => "images/peche.jpg",
            "prix" => 200
        ],
        [
            "id" => 4,
            "idCategorie" => 2,
            "libelle" => "SAMBAROSE",
            "texte" => "Basée sur l'iconique Samba adidas, cette chaussure SAMBAROSE te fait prendre de la hauteur avec sa semelle compensée tendance.",
            "visuel" => "images/carottes.jpg",
            "prix" => 99
        ],
        [
            "id" => 5,
            "idCategorie" => 2,
            "libelle" => "Chaussure de trail-running Terrex Agravic TR UB",
            "texte" => "Le confort et l'accroche pour te sortir des sentiers battus dans un style suède moderne pour la ville. Cette chaussure de trail-running adidas Terrex Agravic TR UB t'assure une foulée tout en souplesse sur le bitume ou le parcours. ",
            "visuel" => "images/tomates.jpg",
            "prix" => 90
        ],
        [
            "id" => 6,
            "idCategorie" => 2,
            "libelle" => "Chaussure Nite Jogger",
            "texte" => "Les détails flashy et réfléchissants sur cette chaussure adidas sont un clin d'œil à la Nite Jogger des 80's.",
            "visuel" => "images/romanesco.jpg",
            "prix" => 130
        ],
        [
            "id" => 7,
            "idCategorie" => 3,
            "libelle" => "DMX Trail Shadow",
            "texte" => "Cette DMX Trail Shadow s'inspire des chaussures de running et de randonnée des années 2000.",
            "visuel" => "images/nutella.jpg",
            "prix" => 180
        ],
        [
            "id" => 8,
            "idCategorie" => 3,
            "libelle" => "Workout Plus",
            "texte" => "Nous allons rappeler aux mordus de sneakers pourquoi notre Workout Plus est une véritable icône, en revenant à l'essentiel. ",
            "visuel" => "images/pizza.jpg",
            "prix" => 90
        ],
        [
            "id" => 9,
            "idCategorie" => 3,
            "libelle" => "Reebok Royal Bridge 3.0",
            "texte" => "Une chaussure de running des 90's sous un nouveau jour. Avec sa tige basse, elle maximise le confort et permet une foulée fluide",
            "visuel" => "images/oreo.jpg",
            "prix" => 75
        ],
    ];
}
