<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Product name is required"
     * )
     * @Assert\Length(
     *     min="2",
     *     minMessage="Product name should be at least 2 characters",
     *     max="32",
     *     maxMessage="Product name cannot be above 32 characters"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     *
     * @Assert\NotBlank(
     *     message="Product description is required"
     * )
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     *
     * @Assert\Type(
     *     type="numeric",
     *     message="Not a valid number"
     * )
     * @Assert\Regex(
     *     "/^\b[0-9]+\b$/",
     *     message="Quantity should be a positive number"
     * )
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     *
     * @Assert\Type(
     *     type="numeric",
     *     message="Not a valid number"
     * )
     * @Assert\Regex(
     *     "/^\b(\d+)(\.(\d){0,2})?\b$/",
     *     message="Up to two digits after the decimal separator"
     * )
     * @Assert\GreaterThan(
     *     value="0",
     *     message="Price should be a positive number above 0"
     * )
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Assert\Image(
     *     mimeTypes={"image/png", "image/jpeg"},
     *     mimeTypesMessage="Only PNG and JPG allowed",
     *     maxSize="5M",
     *     maxSizeMessage="Image should be up to 5 Mbs"
     * )
     */
    private $imageFromForm;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="soldProducts")
     */
    private $user;

    /**
     * @var Review[] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Review", mappedBy="product")
     */
    private $reviews;

    /**
     * @var integer
     */
    private $reviewCount;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="products")
     */
    private $category;

    public function __construct()
    {
        $this->setDateCreated(new \DateTime('now'));
        $this->setReviews(new ArrayCollection());
        $this->category = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * @return \DateTime
     */
    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     * @return Product
     */
    public function setDateCreated(\DateTime $dateCreated): Product
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }


    /**
     * @return string | null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return Product
     */
    public function setImage(string $image): Product
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFromForm()
    {
        return $this->imageFromForm;
    }

    /**
     * @param mixed $imageFromForm
     * @return Product
     */
    public function setImageFromForm($imageFromForm)
    {
        $this->imageFromForm = $imageFromForm;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Product
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Review[]|ArrayCollection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param Review[]|ArrayCollection $reviews
     * @return Product
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
        return $this;
    }

    /**
     * @param Review $review
     * @return Product
     */
    public function addReview($review)
    {
        $this->reviews->add($review);
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Product
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return int
     */
    public function getReviewCount(): int
    {
        return $this->getReviews()->count();
    }

    public function getTimeSpanFromPublishing()
    {
        $dateDiff = $this->getDateCreated()->diff(new \DateTime('now'));

        if (0 !== $dateDiff->days) {
            return $dateDiff->days . ' дена';
        }

        if ($dateDiff->h !== 0) {
            return $dateDiff->h . ' часа';
        };

        if ($dateDiff->i !== 0) {
            return $dateDiff->i . ' минути';
        }

        return $dateDiff->s . ' секунди';
    }
}

