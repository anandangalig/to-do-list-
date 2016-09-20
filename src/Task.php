<?php

    class Task{
        private $description;
        private $id;
        private $category_id;

        function __construct($description, $id = null, $category_id)
        {
            $this->description = $description;
            $this->id = $id;
            $this->category_id = $category_id;
        }

        function getDescription()
        {
            return $this->description;
        }

        function setDescription($new_description)
        {
            $this->description = (string) $new_description;

        }
        function getId()
        {
            return $this->id;
        }

        function getCategoryId()
        {
            return $this->category_id;
        }

        //NOTE: no setter, probably because in memory we want to create tasks with no ID and SQL will assign the IDs in the database... maybe

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO tasks (description, category_id) VALUES ('{$this->getDescription()}', {$this->getCategoryId()})");
            //NOTE: this will sync the local id with the SQL ID
            $this->id = $GLOBALS['DB']->lastInsertId();

        }

        static function getAll()
        {
            $database_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
            // var_dump($database_tasks);

            $database_data = $database_tasks->fetchAll();
            // var_dump("database_data: " . $database_data . "\n");

            $tasks = array();

            // foreach ($database_tasks as $task)
            // {
            //     $description = $task['description'];
            //     $id = $task['id'];
            //     $new_task = new Task($description, $id);
            //     array_push($tasks, $new_task);
            // }
            for ($task_index = 0; $task_index < count($database_data); $task_index++)
            {
                $description = $database_data[$task_index]['description'];
                //   var_dump($description);
                //NOTE: this is getting the id from the database
                $category_id = $database_data[$task_index]['category_id'];
                $id = $database_data[$task_index]['id'];
                $new_task = new Task($description, $id, $category_id);
                //NOTE: This is the same as array push:
                $tasks[] = $new_task;
            }

            return $tasks;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM tasks;");
        }

        static function find($search_id)
        {
            $found_task = null;
            $tasks = Task::getAll();
            for ($task_index = 0; $task_index < count($tasks); $task_index++){
                $current_id = $tasks[$task_index]->getId();
                if ($current_id === $search_id){
                    return $tasks[$task_index];
                }
            }
            print("Could not find task with id: " . $search_id . "\n");
            return null;
        }

    }


 ?>
