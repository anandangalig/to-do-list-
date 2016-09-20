<?php

    class Category{
        private $name;
        private $id;

        function __construct($name, $id=null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getID()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO category (name) VALUES('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $database_categories = $GLOBALS['DB']->query("SELECT * FROM category;");

            $database_data = $database_categories->fetchAll();
            $categories =  array();

            for ($category_index = 0; $category_index < count($database_data); $category_index++)
            {
                $name = $database_data[$category_index]['name'];
                $id = $database_data[$category_index]['id'];
                $new_category = new Category($name, $id);
                $categories[] = $new_category;
            }

            return $categories;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM category;");
        }

        static function find($search_id)
        {
            $found_category = null;
            $categories = Category::getAll();
            for ($category_index = 0; $category_index < count($categories); $category_index++){
                $current_id = $categories[$category_index]->getID();
                if ($current_id === $search_id){
                    return $categories[$category_index];
                }
            }
            print("Could not find task with id: " . $search_id . "\n");
            return null;
        }

    }


 ?>
