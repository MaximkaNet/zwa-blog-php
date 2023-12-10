<?php
namespace app\domain\categories;

require_once "categoryDataSource.php";
require_once "categoryException.php";
require_once "category.php";

use app\core\exception\ApplicationException;

class CategoryMapper
{
    private CategoryDataSource $data_source;

    public function __construct(CategoryDataSource $data_source)
    {
        $this->data_source = $data_source;
    }

    /**
     * Find model in database by id
     * @param int $id
     * @return Category
     * @throws CategoryException
     * @throws ApplicationException
     */
    public function findById(int $id): Category
    {
        $result = $this->data_source->select(null, ["where" => $id]);
        if(empty($result)) throw CategoryException::NotFound();
        return new Category(...$result);
    }

    /**
     * Find all models in database
     * @param array|null $options
     * @return array|null Return models array
     * @throws ApplicationException
     */
    public function findAll(?array $options = null): ?array
    {
        $result = $this->data_source->select(null, $options);
        if(empty($result)) return null;
        $categories = [];
        foreach ($result as $item) {
            $categories[] = new Category(...$item);
        }
        return $categories;
    }

    /**
     * Save model to database
     * @param Category $model
     * @return int Return count rows affected
     * @throws ApplicationException
     */
    public function save(Category $model): int
    {
        $model_to_update = $this->data_source->select(null, ["where" => ["name" => $model->getName()]]);
        if(isset($model_to_update))
            return $this->data_source->update($model->toAssoc(["id"]), ["where" => ["id" => $model_to_update["id"]]]);
        return $this->data_source->insert($model->toAssoc(["id"]));
    }

    /**
     * Delete model from database by id
     * @param int $id
     * @return int Return count rows affected
     * @throws ApplicationException
     */
    public function deleteById(int $id): int
    {
        $where = ["id" => $id];
        $limit = [0, 1];
        return $this->data_source->delete(["where" => $where, "limit" => $limit]);
    }

    /**
     * Delete models from database
     * @param array|null $options
     * @return int Return count rows affected
     * @throws ApplicationException
     */
    public function delete(?array $options = null): int
    {
        return $this->data_source->delete($options);
    }
}