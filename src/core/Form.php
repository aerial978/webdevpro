<?php

namespace src\Core;

class Form
{
    private $formCode = '';

    /**
     * Génére le formulaire HTML
     * @return string
     */
    public function create()
    {
        return $this->formCode;
    }

    /**
     * Valide si tous les champs proposés sont remplis
     *
     * @param array $form Tableau issu du formulaire ($_POST, $_GET)
     * @param array $fields Tableau listant les champs obligatoires
     * @return void
     */
    public static function validate(array $form, array $fields)
    {
        // On   parcourt les champs
        foreach ($fields as $field) {
            // Si le champ est absent ou vide dans le formulaire
            if (!isset($form[$field]) || empty($form[$field])) {
                // On sort en retournant false
                return false;
            }
        }
        return true;
    }

    /**
     * Ajout les attributs envoyés à la balise
     *
     * @param array $attributes Tableau associatif ['class' =>
     * 'form-control', 'required' => true]
     * @return string Chaine de caractères générée
     */
    public function addAttributes(array $attributes): string
    {
        // On initialise une chaine de caractères
        $str = '';

        foreach ($attributes as $attribute => $value) {
            // Si l'attribut est un attribut court sans valeur
            if (is_int($attribute)) {
                $str .= " $value";
            } else {
                // Si l'attribut est fourni avec une valeur spécifique
                if ($value === true) {
                    $str .= " $attribute";
                } else {
                    $str .= " $attribute=\"$value\"";
                }
            }
        }
        return $str;
    }

    /**
     * Balise d'ouverture du formulaire
     * @param string $methode Méthode du formulaire (post ou get)
     * @param string $action Action du formulaire
     * @param array $attributes Attributs
     * @return Form
     */
    public function startForm(string $method = 'post', string $action = '#', array $attributes = []): self
    {
        // On crée la balise form
        $this->formCode .= "<form action='$action' method='$method'";

        // On ajoute les attributs éventuels
        $this->formCode .= $attributes ? $this->addAttributes($attributes) . '>' : '>';

        return $this;
    }

    /**
     * Balise de fermeture du formulaire
     *
     * @return Form
     */
    public function endForm(): self
    {
        $this->formCode .= '</form>';
        return $this;
    }

    public function addLabelFor(string $for, string $text, array $attributes = []): self
    {
        // On ouvre la balise
        $this->formCode .= "<label for='$for'";

        // On ajoute les attributs
        $this->formCode .= $attributes ? $this->addAttributes($attributes) : '';

        // On ajoute le texte
        $this->formCode .= ">$text</label>";

        return $this;
    }

    public function addInput(string $type, string $name, array $attributes = []): self
    {
        // On ouvre la balise
        $this->formCode .= "<input type='$type' name='$name'";

        // On ajoute les attributs
        $this->formCode .= $attributes ? $this->addAttributes($attributes) . '>' : '>';

        return $this;
    }

    public function addError($fieldError, $attributes = []): self
    {
        $this->formCode .= "<div id='$fieldError' ";

        $this->formCode .= $attributes ? $this->addAttributes($attributes) : '';

        $this->formCode .= '></div>';

        return $this;
    }

    public function addTextarea(string $name, string $value = '', array $attributes = []): self
    {
        // On ouvre la balise
        $this->formCode .= "<textarea name='$name'";

        // On ajoute les attributs
        $this->formCode .= $attributes ? $this->addAttributes($attributes) : '';

        // On ajoute le texte
        $this->formCode .= ">$value</textarea>";

        return $this;
    }

    public function addSelect(string $name, array $options, array $attributes = []): self
    {
        // On crée le select
        $this->formCode .= "<select name='$name'";

        // On ajoute les attributs
        $this->formCode .= $attributes ? $this->addAttributes($attributes) . '>' : '>';

        // On ajoute les options
        foreach ($options as $value => $text) {
            $this->formCode .= "<option value=\"$value\">$text</option>";
        }

        // On ferme le select
        $this->formCode .= '</select>';

        return $this;
    }

    public function addButton(string $text, string $type, array $attributes = []): self
    {
        // On ouvre le bouton
        $this->formCode .= "<br><button type='$type' ";

        // On ajoute les attributs
        $this->formCode .= $attributes ? $this->addAttributes($attributes) : '';

        // On ajoute le texte et on ferme
        $this->formCode .= ">$text</button>";

        return $this;
    }
}