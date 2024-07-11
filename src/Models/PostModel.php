<?php

namespace src\Models;

use src\Models\Model;
use DateTime;
use HTMLPurifier;
use HTMLPurifier_Config;

class PostModel extends Model
{
    protected $id;
    protected $title;
    protected $post_content;
    protected $category_id;
    protected $post_status;
    protected $post_image;
    protected $user_id;
    protected $created_at_post;
    protected $updated_at_post;

    public function __construct()
    {
        $this->table = "Post";
    }

    // PostController => index()
    public function findAllPost()
    {
        $sql = "SELECT *, DATE_FORMAT(created_at_post, '%d/%m/%Y %H:%i:%s') AS date_create, post.id AS postId FROM {$this->table}
                JOIN user ON post.user_id = user.id
                JOIN category ON post.category_id = category.id";

        $query = $this->request($sql);

        return $query->fetchAll();
    }

    // PostController => create()
    public function createPost()
    {
        $sql = "INSERT INTO {$this->table} (title, post_content, category_id, post_status, post_image, user_id, created_at_post)
        VALUES (:title, :postContent, :category, :postStatus, :postImage, :user_id, NOW())";

        $attributs = [
            ':title' => $this->title,
            ':postContent' => $this->post_content,
            ':category' => $this->category_id,
            ':postStatus' => $this->post_status,
            ':postImage' => $this->post_image,
            ':user_id' => $this->user_id,
        ];

        $query = $this->request($sql, $attributs);

        return $query;
    }

    // ??????????????????
    // Assainir le contenu HTML de postContent (CkEditor) pour prévenir les attaques XSS avec HTMLPurifier
    public function sanitizeContent($content)
    {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($content);
    }

    // PostController => create()
    public function getLastInsertedPostId()
    {
        $sql = "SELECT id FROM {$this->table} ORDER BY id DESC LIMIT 1";
        $query = $this->request($sql);
        $result = $query->fetch();

        return ($result) ? $result->id : null;
    }

    // HomeController => index() & PostController => create()
    public function getPosts($offset = 0, $limit = 10)
    {
        $sql = "SELECT *, DATE_FORMAT(updated_at_post, '%d/%m') AS date_update, post.id AS postId 
                FROM {$this->table}  
                LEFT JOIN category ON post.category_id = category.id
                GROUP BY post.id ORDER BY date_update ASC
                LIMIT $offset, $limit";

        $query = $this->request($sql);

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    // PostController => PostList()
    public function countPosts()
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $query = $this->request($sql);

        return $query->fetchColumn();
    }

    // PostController => PostList()
    public function timeElapsedString($datetime, $full = false)
    {
        if (is_null($datetime)) {
            return 'unknown';
        }

        $now = new DateTime();
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if ($diff->d >= 7) {
            $weeks = floor($diff->d / 7);
            $string['w'] = $weeks . ' week' . ($weeks > 1 ? 's' : '');
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    // PostController => PostList()
    public function searchPosts($keyword, $offset = 0, $limit = 10)
    {
        $keyword = $_GET['search'];

        $sql = "SELECT *, DATE_FORMAT(updated_at_post, '%d/%m') AS date_update, post.id AS postId
                FROM {$this->table}
                LEFT JOIN category ON post.category_id = category.id
                WHERE post.title LIKE '%$keyword%'
                   OR post.post_content LIKE '%$keyword%'
                GROUP BY post.id
                ORDER BY date_update ASC LIMIT $offset, $limit";

        $query = $this->request($sql);

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of content
     */
    public function getPostContent()
    {
        return $this->post_content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */
    public function setPostContent($post_content)
    {
        $this->post_content = $post_content;

        return $this;
    }

    /**
     * Get the value of category_id
     */
    public function getCategory_id(): int
    {
        return $this->category_id;
    }

    /**
     * Set the value of category_id
     *
     * @return  self
     */
    public function setCategory_id(int $category_id)
    {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getPostStatus()
    {
        return $this->post_status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setPostStatus($post_status)
    {
        $this->post_status = $post_status;

        return $this;
    }

    /**
     * Get the value of postImage
     */
    public function getPostImage()
    {
        return $this->post_image;
    }

    /**
     * Set the value of postImage
     *
     * @return  self
     */
    public function setPostImage($post_image)
    {
        $this->post_image = $post_image;

        return $this;
    }

    /**
     * Get the value of user_id
     */
    public function getUser_id(): int
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */
    public function setUser_id(int $user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of date
     */
    public function getCreated_at_post()
    {
        return $this->created_at_post;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */
    public function setCreated_at_post()
    {
        $this->created_at_post = date('Y-m-d H:i:s');

        return $this;
    }

    /**
     * Get the value of update
     */
    public function getUpdated_at_post()
    {
        return $this->updated_at_post;
    }

    /**
     * Set the value of update
     *
     * @return  self
     */
    public function setUpdated_at_post()
    {
        $this->updated_at_post = date('Y-m-d H:i:s');

        return $this;
    }
}
