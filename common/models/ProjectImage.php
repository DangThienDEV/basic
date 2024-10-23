<?php

namespace common\models; // Chỉ định không gian tên cho mô hình, giúp tổ chức mã nguồn.

use Yii; // Nhập lớp Yii, cung cấp các chức năng tiện ích cho ứng dụng.

/**
 * Đây là lớp mô hình cho bảng "project_image".
 *
 * @property int $id // ID của hình ảnh dự án.
 * @property int $project_id // ID của dự án mà hình ảnh thuộc về.
 * @property int $file_id // ID của tệp hình ảnh.
 *
 * @property File $file // Mối quan hệ với mô hình File.
 * @property Project $project // Mối quan hệ với mô hình Project.
 */
class ProjectImage extends \yii\db\ActiveRecord // Lớp ProjectImage kế thừa từ ActiveRecord, cho phép tương tác với cơ sở dữ liệu.
{
    /**
     * {@inheritdoc}
     */
    public static function tableName() // Phương thức xác định tên bảng trong cơ sở dữ liệu.
    {
        return 'project_image'; // Trả về tên bảng "project_image".
    }

    /**
     * {@inheritdoc}
     */
    public function rules() // Phương thức xác định các quy tắc xác thực cho mô hình.
    {
        return [
            [['project_id', 'file_id'], 'required'], // Các thuộc tính này là bắt buộc.
            [['project_id', 'file_id'], 'integer'], // Các thuộc tính này phải là số nguyên.
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['file_id' => 'id']], // Kiểm tra xem file_id có tồn tại trong bảng File không.
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']], // Kiểm tra xem project_id có tồn tại trong bảng Project không.
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() // Phương thức cung cấp nhãn cho các thuộc tính.
    {
        return [
            'id' => Yii::t('app', 'ID'), // Nhãn cho ID.
            'project_id' => Yii::t('app', 'Project ID'), // Nhãn cho ID dự án.
            'file_id' => Yii::t('app', 'File ID'), // Nhãn cho ID tệp.
        ];
    }

    /**
     * Gets query for [[File]]. // Phương thức để lấy truy vấn cho File.
     *
     * @return \yii\db\ActiveQuery // Trả về đối tượng ActiveQuery cho File.
     */
    public function getFile() // Phương thức định nghĩa mối quan hệ với File.
    {
        return $this->hasOne(File::class, ['id' => 'file_id']); // Liên kết với tệp hình ảnh thông qua file_id.
    }

    /**
     * Gets query for [[Project]]. // Phương thức để lấy truy vấn cho Project.
     *
     * @return \yii\db\ActiveQuery // Trả về đối tượng ActiveQuery cho Project.
     */
    public function getProject() // Phương thức định nghĩa mối quan hệ với Project.
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']); // Liên kết với dự án thông qua project_id.
    }
}
