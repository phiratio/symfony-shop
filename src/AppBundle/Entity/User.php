<?php

namespace AppBundle\Entity;

use AppBundle\Form\ReviewAddType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    const DEFAULT_BALANCE = 777;

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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(
     *     message="Username field cannot be empty"
     * )
     * @Assert\Length(
     *     min="4",
     *     max="32",
     *     minMessage="Username should be at least 4 characters long",
     *     maxMessage="Username cannot be longer than 32 characters"
     * )
     * @Assert\Regex(
     *     "/^\b[A-za-z\d]+\b$/",
     *      message="Username should contain only alphabet characters and digits"
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Please enter a password"
     * )
     * @Assert\Length(
     *     min="4",
     *     max="26",
     *     minMessage="Password should be at least 4 characters long",
     *     maxMessage="Password too long, up to {{ limit }} characters allowed"
     * )
     */
    private $rawPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(
     *     message="Enter your email"
     * )
     * @Assert\Email(
     *     message="Not a valid email"
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     *
     * @Assert\Length(
     *     min="2",
     *     max="32",
     *     minMessage="First name can't be less than 2 characters long",
     *     maxMessage="First name cannot be longer than 32 characters"
     * )
     * @Assert\Regex(
     *     "/^\b[A-za-z]+\b$/",
     *      message="First name should contain only alphabet characters"
     * )
     * @Assert\NotBlank(
     *     message="First name is required"
     * )
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     *
     * @Assert\Length(
     *     min="2",
     *     max="32",
     *     minMessage="Last name can't be less than 2 characters long",
     *     maxMessage="Last name cannot be longer than 32 characters"
     * )
     * @Assert\Regex(
     *     "/^\b[A-za-z]+\b$/",
     *      message="Last name should contain only alphabet characters"
     * )
     * @Assert\NotBlank(
     *     message="Last name is required"
     * )
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     *
     * @Assert\Country(
     *     message="Not a valid country"
     * )
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="shippingAddress", type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Shipping address is required"
     * )
     */
    private $shippingAddress;

    /**
     * @var float
     *
     *
     *
     * @ORM\Column(name="balance", type="decimal", precision=10, scale=2)
     */
    private $balance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateRegistered", type="datetime")
     */
    private $dateRegistered;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(
     *      name="users_roles",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    /**
     * @var ArrayCollection | Product[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="user")
     */
    private $soldProducts;

    /**
     * @var ArrayCollection | Review[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Review", mappedBy="user")
     */
    private $reviews;

    public function __construct()
    {
        $this
            ->setBalance(self::DEFAULT_BALANCE)
            ->setDateRegistered(new \DateTime('now'))
            ->setRoles(new ArrayCollection())
            ->setReviews(new ArrayCollection());
    }

    public function setId($id)
    {
        $this->id = $id;
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set shippingAddress
     *
     * @param string $shippingAddress
     *
     * @return User
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * Get shippingAddress
     *
     * @return string
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Set balance
     *
     * @param float $balance
     *
     * @return User
     */
    public function setBalance($balance)
    {
        $this->balance = floatval($balance);

        return $this;
    }

    /**
     * Get balance
     *
     * @return float
     */
    public function getBalance()
    {
        return number_format(floatval($this->balance), 2);
    }

    /**
     * Set dateRegistered
     *
     * @param \DateTime $dateRegistered
     *
     * @return User
     */
    public function setDateRegistered($dateRegistered)
    {
        $this->dateRegistered = $dateRegistered;

        return $this;
    }

    /**
     * Get dateRegistered
     *
     * @return \DateTime
     */
    public function getDateRegistered()
    {
        return $this->dateRegistered;
    }

    /**
     * @return string
     */
    public function getRawPassword()
    {
        return $this->rawPassword;
    }

    /**
     * @param string $rawPassword
     * @return User
     */
    public function setRawPassword($rawPassword)
    {
        $this->rawPassword = $rawPassword;
        return $this;
    }

    /**
     * @param ArrayCollection $roles
     * @return User
     */
    public function setRoles(ArrayCollection $roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @param Role $role
     * @return User
     */
    public function addRole($role)
    {
        $this->roles->add($role);
        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return ArrayCollection | Role[]
     */
    public function getRoles()
    {
        $roles = array();

        /** @var Role $role $role */
        foreach ($this->roles->toArray() as $role) {
            $roles[] = $role->getName();
        }

        return $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return null;
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getSoldProducts()
    {
        return $this->soldProducts;
    }

    /**
     * @param Product[]|ArrayCollection $soldProducts
     * @return User
     */
    public function setSoldProducts($soldProducts)
    {
        $this->soldProducts = $soldProducts;
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
     * @return User
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
        return $this;
    }
}

