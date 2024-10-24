<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;


/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property string $tech_stack
 * @property string $descritption
 * @property string|null $start_date
 * @property string|null $end_date
 *
 * @property ProjectImage[] $Images
 * @property Testimonial[] $testimonials
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile;
     */
    public $imageFile;
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'tech_stack', 'descritption'], 'required'],
            [['tech_stack', 'descritption'], 'string'],
            [['start_date', 'end_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => false,  'extensions' => 'png, jpg, jpeg' , 'maxFiles' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'tech_stack' => Yii::t('app', 'Tech Stack'),
            'descritption' => Yii::t('app', 'Descritption'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
        ];
    }

    /**
     * Gets query for [[ProjectImages]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ProjectImage::class, ['project_id' => 'id']);
    }

    /**
     * Gets query for [[Testimonials]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getTestimonials()
    {
        return $this->hasMany(Testimonial::class, ['project_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }

    public function saveImage(){
        Yii::$app->db->transaction(function($db){
            foreach($this->imageFile as $imagesFile){
                $file = new File();
                $file -> name = uniqid(true). '.' . $imagesFile->extension;
                $file -> base_url = Yii::$app->urlManager->createAbsoluteUrl('uploads/projects');
                $file -> mine_type = mime_content_type($imagesFile->tempName);
                $file -> save();
        
                $projectImage = new ProjectImage();
                $projectImage->project_id = $this->id;
                $projectImage-> file_id = $file-> id;
                $projectImage->save();
        
                if(!$imagesFile->saveAs('uploads/projects/'. $file->name)){
                     $db->transaction->rollback();
                }
             
            }
        });
    }
    public function hasImages(){
        // trả về true để lấy số lượng hình ảnh nếu nó lớnhơn 0
        return count($this->images) > 0;
    }
    public function imageAbsoluterUrls(){
        $urls  = [];
        foreach($this->images as $image){
            $urls[] = $image->file->absoluterUrl();

        }
        return $urls;

    }
    public function imageConfig(){
        $config = [];
        foreach($this->images as $image){
            $config[]= [
                'key' => $image->id,
            ];
        }
        return $config;
    }
    public function loadUploadedImagefiles(){
        $this->imageFile = UploadedFile::getInstances($this,'imageFile');
    }
}