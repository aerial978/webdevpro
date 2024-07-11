<?php

namespace src\Models;

use src\Models\Model;

class TagModel extends Model
{
    protected $id;
    protected $tag_name;

    public function __construct()
    {
        $this->table = "tag";
    }

    // TagController => index()
    public function findTagList()
    {
        $sql = "SELECT tag_name, COUNT(post_tag.post_id) AS post_count, tag.id AS tagId FROM {$this->table}
        LEFT JOIN post_tag ON tag.id = post_tag.tag_id
        GROUP BY tag_name ORDER BY tag_name ASC";

        $query = $this->request($sql);

        return $query->fetchAll();
    }

    // PostController => edit()
    public function getTagsForPost($postId)
    {
        $sql = "SELECT * FROM {$this->table} 
            INNER JOIN post_tag ON tag.id = post_tag.tag_id
            WHERE post_tag.post_id = :post_id";

        $params = [':post_id' => $postId];

        $query = $this->request($sql, $params);
        $tags = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $tags;
    }

    // PostController => edit()
    public function getAllTagNames()
    {
        $sql = "SELECT tag_name FROM {$this->table}";
        $query = $this->request($sql);
        return $query->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    // PostController => edit()
    public function getTagIdByTagName($tag_name)
    {
        $sql = "SELECT id FROM {$this->table} WHERE tag_name = :tag_name";
        $params = [':tag_name' => $tag_name];
        $query = $this->request($sql, $params);
        return $query->fetch();
    }

    // PostController => postList()
    public function getTagFrequencies()
    {
        // Requête SQL pour obtenir le nom de chaque tag et le nombre de fois où il est utilisé, y compris les tags non utilisés
        $sql = "SELECT tag_name, COUNT(post_tag.tag_id) as frequency 
                FROM {$this->table} 
                LEFT JOIN post_tag ON tag.id = post_tag.tag_id 
                GROUP BY tag_name";

        // Exécution de la requête
        $query = $this->request($sql);

        // Récupération des résultats en tant que tableau associatif [ 'tag1' => 10, 'tag2' => 20, ...] et
        // Retourne le tableau des tags avec leurs fréquences
        return $query->fetchAll(\PDO::FETCH_KEY_PAIR);
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
     * Get the value of tag_name
     */
    public function getTagName()
    {
        return $this->tag_name;
    }

    /**
     * Set the value of tag_name
     *
     * @return  self
     */
    public function setTagName($tag_name)
    {
        $this->tag_name = strtolower($tag_name);

        return $this;
    }
}
