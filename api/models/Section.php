<?php

namespace api\models;
use Yii;

class Section extends \common\models\Section
{
    public $orders;

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $orders = $this->getDirtyAttributes(['orders']);
            if (!empty($orders)) {
                $this->reOrder($orders);
            }
            $parent = $this->getDirtyAttributes(['parent']);
            if (!empty($parent)) {
                $ancestor = $this->find()->where(['id' => $parent])->one();
                $this->ancestor = $ancestor->ancestor; 
            }
            return true;
        }
        return false;
    }

    protected function reOrder() {
        $prevId = null;
        $nextId = null;
        $stop = false;
        foreach ($this->orders as $order) {
            if ($stop) {
                $nextId = $order;
                break;
            }
            if ($order !== $this->id) {
                $prevId = $order;
            } else {
                $stop = true;
            }
        }

        if ($prevId !== null) {
            $this->getDb()->createCommand()
                ->update(self::tableName(), ['next' => $this->id], ['id' => $prevId])
                ->execute();
        }
        if ($nextId !== null) {
            $this->getDb()->createCommand()
                ->update(self::tableName(), ['prev' => $this->id], ['id' => $nextId])
                ->execute();
        }
        if ($this->prev !== null) {
            $this->getDb()->createCommand()->update(self::tableName(),
                    ['next' => $this->next],
                    ['id' => $this->prev]
                )->execute();
        }
        if ($this->next !== null) {
            $this->getDb()->createCommand()->update(self::tableName(),
                    ['prev' => $this->prev],
                    ['id' => $this->next]
                )->execute();
        }
    }
}
