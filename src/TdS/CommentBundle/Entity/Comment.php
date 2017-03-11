<?php
namespace TdS\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Comment as BaseComment;
use FOS\CommentBundle\Model\SignedCommentInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* @ORM\Entity
* @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Comment extends BaseComment implements SignedCommentInterface{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Thread
     * @ORM\ManyToOne(targetEntity="TdS\CommentBundle\Entity\Thread")
     */
    protected $thread;

    /**
     * Author of the comment
     *
     * @ORM\ManyToOne(targetEntity="TdS\UserBundle\Entity\User")
     * @var User
     */
    protected $author;


    /**
     * Comment text
     *
     * @var string
     */
    protected $body;




    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getAuthorName()
    {
        if (null === $this->getAuthor()) {
            return 'Anonymous';
        }

        return $this->getAuthor()->getUsername();
    }


    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param  string
     * @return null
     */
    public function setBody($body)
    {
        $body = nl2br(htmlentities($body, ENT_QUOTES, 'UTF-8'));
        $body = preg_replace_callback('#(?:https?://\S+)|(?:www.\S+)|(?:\S+\.\S+)#', function($arr){
            // if(strpos($arr[0], 'http://') !== 0)
            // {
            //     $arr[0] = 'http://' . $arr[0];
            // }
            $url = parse_url($arr[0]);

            // images
            if(preg_match('#\.(png|jpg|gif)$#', $url['path']))
            {
                return '<img src="'. $arr[0] . '" />';
            }
            // youtube
            if(in_array($url['host'], array('www.youtube.com', 'youtube.com'))
              && $url['path'] == '/watch'
              && isset($url['query']))
            {
                parse_str($url['query'], $query);
                return sprintf('<div class="video-container"><iframe class="embedded-video" src="https://www.youtube.com/embed/%s" frameborder="0" allowfullscreen></iframe></div>', $query['v']);
                

            }
            //links
            return sprintf('<a href="%1$s">%1$s</a>', $arr[0]);
        }, $body);
        $this->body = $body;
    }
}

?>