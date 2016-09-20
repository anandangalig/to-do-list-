<?php
    /**
    *@backupGlobals disabled
    *@backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";
    //Server must reference local host on MAMP port.
    $server = 'mysql:host=localhost:8889;dbname=todo_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO ($server, $username, $password);

    class TaskTest extends PHPUnit_Framework_TestCase
    {

        //NOTE: CLEAN UP DATABASE!!!
        protected function teardown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        function test_getId()
        {
            //ARRANGE
            $name = "home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = 'wash the cat';
            $category_id = $test_category->getId();
            $expected_output = true;
            $test_task = new Task($description, $id, $category_id);
            $test_task->save();
            //ACT
            $result = $test_task->getId();
            //ASSERT
            $this->assertEquals($expected_output, is_numeric($result));
        }

        function test_getCategoryId()
        {
            //Arrange
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $category_id = $test_category->getId();
            $test_task = new Task($description, $id, $category_id);
            $test_task->save();

            //Act
            $result = $test_task->getCategoryId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            //ARRANGE
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();


            $description ='wash the cat';
            $category_id = $test_category->getId();
            $test_task = new Task($description, $id, $category_id);
            $expected_output = $test_task;
            //ACT
            $test_task->save();
            //ASSERT
            //NOTE: Result is an array that contains instances of task objects.
            $result = Task::getAll();
            //$result[0] takes the first instance inside of the results array (references tasks in task.php)
            $this->assertEquals($expected_output, $result[0]);
          }

        function test_getAll()
        {
            //ARRANGE
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description ='wash the cat';
            $description2 ='wash the dog';
            $category_id = $test_category->getId();
            $test_task = new Task($description, $id, $category_id);
            $test_task2 = new Task($description2, $id, $category_id);
            $expected_output = [$test_task, $test_task2];
            $test_task->save();
            $test_task2->save();

            //ACT
            $result = Task::getAll();
            // var_dump($result);

            //ASSERT
            $this->assertEquals($expected_output, $result);

        }

        function test_deleteAll()
        {
            //ARRANGE
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $category_id = $test_category->getId();
            $description = 'wash the cat';
            $description2 = 'wash the dog';
            $test_task = new Task ($description, $id, $category_id);
            $test_task->save();
            $test_task2 = new Task ($description, $id, $category_id);
            $test_task2->save();
            $expected_output = [];

            //ACT
            Task::deleteAll();

            //ASSERT
            $result = Task::getAll();
            $this->assertEquals($expected_output, $result);

        }

        function test_find()
        {
            //ARRANGE
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $category_id = $test_category->getId();
            $description = 'wash the cat';
            $description2 = 'wash the dog';
            $test_task = new Task ($description, $id, $category_id);
            $test_task->save();
            $test_task2 = new Task ($description2, $id, $category_id);
            $test_task2->save();
            $expected_output = $test_task;

            //ACT
            $id = $test_task->getId();
            $result = Task::find($id);

            //ASSERT
            $this->assertEquals($expected_output, $result);

        }
    }
?>
