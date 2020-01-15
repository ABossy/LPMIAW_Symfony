<?php
namespace App\Service;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\User;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class PanierService {
    const PANIER_SESSION = 'panier';
    /**
     * @var SessionInterface
     */
    private $session;
    private $repo;
    private $panier;
    private $em;

    public function __construct(
        SessionInterface $session,
        ProduitRepository $repo,
        EntityManagerInterface $em

    ) {
        $this->session = $session;
        $this->repo = $repo;
        $this->panier = $this->session->get(self::PANIER_SESSION, []);
        $this->em = $em;
    }
    /**
     * @return array
     */
    public function getContenu(): array
    {
        return $this->session->get(self::PANIER_SESSION, []);
    }
    /**
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getContenu() as $id => $quantity) {
            $total += $this->repo->findOneById($id)->getPrix() * $quantity;
        }
        return $total;
    }
    /**
     * @return int
     */
    public function getNbProduits(): int
    {
        $nbProduits = 0;
        foreach ($this->getContenu() as $quantity) {
            $nbProduits += $quantity;
        }
        return $nbProduits;
    }
    public function getNbProduit(int $productId): int
    {
        if (isset($this->getContenu()[$productId])) {
            return $this->getContenu()[$productId];
        } else {
            return 0;
        }
    }
    /**
     * @param int $idProduit
     * @param int $quantity
     */
    public function ajouterProduit(int $idProduit, int $quantity = 1): void
    {
        if (isset($this->panier[$idProduit])) {
            $initialQuantity = $this->panier[$idProduit];
        } else {
            $initialQuantity = null;
        }
        if ($initialQuantity !== null) {
            $this->panier[$idProduit] += $quantity;
        } else {
            $this->panier[$idProduit] = $quantity;
        }
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }
    /**
     * @param int $idProduit
     * @param int $quantity
     */
    public function enleverProduit(int $idProduit, int $quantity = 1): void
    {
        if (isset($this->panier[$idProduit])) {
            $initialQuantity = $this->panier[$idProduit];
        } else {
            $initialQuantity = 0;
        }
        if ($initialQuantity !== null && $initialQuantity > $quantity) {
            $this->panier[$idProduit] -= $quantity;
        } else {
            unset($this->panier[$idProduit]);
        }
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }
    /**
     * @param int $idProduit
     */
    public function supprimerProduit(int $idProduit): void
    {
        unset($this->panier[$idProduit]);
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    public function removeall():void
    {
        $this->panier = [];
        $this->session->set(self::PANIER_SESSION, $this->panier);

    }

    public function panierToCommande(User $user){
        $commande = new Commande();
        $commande->setUser($user);
        $commande->setDateCommande(new \DateTime());
        $commande->setStatut('En attente');

        foreach ($this->panier  as $produitId => $quantite) {
            $prod = $this->repo->findOneById($produitId);
            $ligne = new LigneCommande();
            $ligne->setCommande($commande);
            $ligne->setPrix($prod->getPrix());
            $ligne->setProduit($prod);
            $ligne->setQuantite($quantite);
            $this->em->persist($ligne);
        }
        $this->em->persist($commande);
        $this->em->flush();

        $this->removeall();
    }

}