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

        function getId()
        {
            return $this->id;
        }

        function getTasks()
        {
            $tasks = Array();
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE category_id = {$this->getId()};");
            foreach($returned_tasks as $task) {
                $description = $task['description'];
                $id = $task['id'];
                $category_id = $task['category_id'];
                $due_date= $task['due_date'];
                $new_task = new Task($description, $id, $category_id, $due_date);
                array_push($tasks, $new_task);
            }
            return $tasks;
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
                $current_id = $categories[$category_index]->getId();
                if ($current_id === $search_id){
                    return $categories[$category_index];
                }
            }
            print("Could not find task with id: " . $search_id . "\n");
            return null;
        }

    }


 ?>
