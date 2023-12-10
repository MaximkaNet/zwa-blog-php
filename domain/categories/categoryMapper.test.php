<?php

namespace test\mapper;

require_once "../../core/test.php";

use app\domain\entity\Category;
use test\Test;

require_once "categoryMapper.php";
use app\domain\mapper\CategoryMapper;

define("PDO_DSN" , "mysql:host=localhost;dbname=zwa-blog");
define("PDO_USER", "root");
define("PDO_PASS", "");

$test_handler = new Test();

$test_handler->addTest("Save category", function (){
    $mapper = new CategoryMapper(new \PDO(PDO_DSN, PDO_USER, PDO_PASS));
    $category = new Category(null, "cpp", "C ++");
    $mapper->save($category);
});
$test_handler->addTest("Save category", function (){
    $mapper = new CategoryMapper(new \PDO(PDO_DSN, PDO_USER, PDO_PASS));
    $category = new Category(null, "python", "Python");
    $mapper->save($category);
});
$test_handler->addTest("Save category", function (){
    $mapper = new CategoryMapper(new \PDO(PDO_DSN, PDO_USER, PDO_PASS));
    $category = new Category(null, "data_science", "Data science");
    $mapper->save($category);
});
$test_handler->addTest("Save category", function (){
    $mapper = new CategoryMapper(new \PDO(PDO_DSN, PDO_USER, PDO_PASS));
    $category = new Category(null, "computer_science", "Computer scienceddd");
    $mapper->save($category);
});
$test_handler->addTest("Select category by id (2)", function (){
    try {
        $mapper = new CategoryMapper(new \PDO(PDO_DSN, PDO_USER, PDO_PASS));
        $category = $mapper->findById(37);
        return var_export($category, true);
    } catch (\Exception $e){
        if($e->getCode() == 404)
            return $e->getMessage();
        throw $e;
    }
});
$test_handler->addTest("Select category by id (3)", function (){
    try {
        $mapper = new CategoryMapper(new \PDO(PDO_DSN, PDO_USER, PDO_PASS));
        $category = $mapper->findById(3);
        return var_export($category, true);
    } catch (\Exception $e){
        if($e->getCode() !== 404)
            throw $e;
        return $e->getMessage();
    }
});
$test_handler->addTest("Select all categories", function (){
    $mapper = new CategoryMapper(new \PDO(PDO_DSN, PDO_USER, PDO_PASS));
    $categories = $mapper->findAll();
    return var_export($categories, true);
});
$test_handler->addTest("Delete by id", function (){
    $mapper = new CategoryMapper(new \PDO(PDO_DSN, PDO_USER, PDO_PASS));
    return $mapper->deleteById(1);
});
$test_handler->addTest("Delete all", function (){
    $mapper = new CategoryMapper(new \PDO(PDO_DSN, PDO_USER, PDO_PASS));
    return $mapper->delete();
});
$test_handler->start();