<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $name
 * @property integer $frequency
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['frequency'], 'integer'],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'frequency' => 'Frequency',
        ];
    }

    public function array2string($array){
        return implode(',',$array);
    }
    public function string2array($string){
        return explode(',',$string);
    }

    /**
     * 新增标签
     */
    public function addTag($tags){
        if (empty($tags)) return ;
        $tag = Tag::find()->where(['in','name',$tags])->indexBy('name')->all();
        $different = array_diff($tags,$tag);//array_diff()计算数组的差集
        $intersect = array_intersect($tags,$tag);//array_intersect()计算数组的交集
        if (!$different){
            foreach ($different as $name){
                $Tag = new Tag();
                $Tag->name = $name;
                $Tag->frequency = 1;
                $Tag->save();
            }
        }
        if (!$intersect){
            foreach ($intersect as $name){
                $tag[$name]->frequency += 1;
                $tag[$name]->save();
            }
        }
    }
    /**
     * 删除标签
     */
    public function removeTag($tags){
        if (empty($tags)) return ;
        $tag = Tag::find()->where(['in','name',$tags])->all();

        if (!$tag){
            foreach ($tag as $k=>$v){
                if ($tag[$k]->frequency>1){
                    $tag[$k]->frequency -= 1;
                    $tag[$k]->save();
                }else{
                    $tag[$k]->delete();
                }

            }
        }

    }


}
