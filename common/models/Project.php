<?php

namespace common\models; // Chỉ định không gian tên cho mô hình, giúp tổ chức mã nguồn.

use Yii; // Nhập lớp Yii, cung cấp các chức năng tiện ích cho ứng dụng.
use yii\web\UploadedFile; // Nhập lớp UploadedFile để xử lý tệp tải lên.

/**
 * Đây là lớp mô hình cho bảng "project".
 *
 * @property int $id // ID của dự án.
 * @property string $name // Tên dự án.
 * @property string $tech_stack // Công nghệ sử dụng trong dự án.
 * @property string $description // Mô tả dự án.
 * @property string|null $start_date // Ngày bắt đầu dự án.
 * @property string|null $end_date // Ngày kết thúc dự án.
 *
 * @property ProjectImage[] $projectImages // Mối quan hệ với mô hình ProjectImage.
 * @property Testimonial[] $testimonials // Mối quan hệ với mô hình Testimonial.
 */
class Project extends \yii\db\ActiveRecord // Lớp Project kế thừa từ ActiveRecord, cho phép tương tác với cơ sở dữ liệu.
{
    /**
     * @var UploadedFile; // Khai báo thuộc tính để lưu trữ tệp hình ảnh tải lên.
     */
    public $imageFile; // Thuộc tính để lưu trữ tệp hình ảnh.

    /**
     * {@inheritdoc}
     */
    public static function tableName() // Phương thức xác định tên bảng trong cơ sở dữ liệu.
    {
        return 'project'; // Trả về tên bảng "project".
    }

    /**
     * {@inheritdoc}
     */
    public function rules() // Phương thức xác định các quy tắc xác thực cho mô hình.
    {
        return [
            [['name', 'tech_stack', 'description'], 'required'], // Các thuộc tính này là bắt buộc.
            [['tech_stack', 'description'], 'string'], // Các thuộc tính này phải là chuỗi.
            [['start_date', 'end_date'], 'safe'], // Các thuộc tính này có thể chứa dữ liệu không được xác thực.
            [['name'], 'string', 'max' => 255], // Tên phải là chuỗi với độ dài tối đa là 255 ký tự.
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'], // Tệp tải lên phải là một trong các định dạng hình ảnh.
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() // Phương thức cung cấp nhãn cho các thuộc tính.
    {
        return [
            'id' => Yii::t('app', 'ID'), // Nhãn cho ID.
            'name' => Yii::t('app', 'Name'), // Nhãn cho Tên.
            'tech_stack' => Yii::t('app', 'Tech Stack'), // Nhãn cho Công nghệ.
            'description' => Yii::t('app', 'Description'), // Nhãn cho Mô tả.
            'start_date' => Yii::t('app', 'Start Date'), // Nhãn cho Ngày bắt đầu.
            'end_date' => Yii::t('app', 'End Date'), // Nhãn cho Ngày kết thúc.
        ];
    }

    /**
     * Gets query for [[ProjectImages]]. // Phương thức để lấy truy vấn cho ProjectImages.
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery // Trả về đối tượng ActiveQuery cho ProjectImage.
     */
    public function getProjectImages() // Phương thức định nghĩa mối quan hệ với ProjectImage.
    {
        return $this->hasMany(ProjectImage::class, ['project_id' => 'id']); // Trả về tất cả hình ảnh liên quan đến dự án.
    }

    /**
     * Gets query for [[Testimonials]]. // Phương thức để lấy truy vấn cho Testimonials.
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery // Trả về đối tượng ActiveQuery cho Testimonial.
     */
    public function getTestimonials() // Phương thức định nghĩa mối quan hệ với Testimonial.
    {
        return $this->hasMany(Testimonial::class, ['project_id' => 'id']); // Trả về tất cả đánh giá liên quan đến dự án.
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuery the active query used by this AR class. // Trả về truy vấn tùy chỉnh cho mô hình Project.
     */
    public static function find() // Phương thức trả về đối tượng truy vấn tùy chỉnh.
    {
        return new ProjectQuery(get_called_class()); // Trả về đối tượng ProjectQuery cho lớp này.
    }

    public function saveImage() // Phương thức để lưu tệp hình ảnh tải lên.
    {
        Yii::$app->db->transaction(function($db) { // Bắt đầu một giao dịch cơ sở dữ liệu.
            $file = new File(); // Tạo một đối tượng File mới.
            $file->name = uniqid(true) . '.' . $this->imageFile->extension; // Tạo tên tệp ngẫu nhiên với phần mở rộng.
            $file->base_url = Yii::$app->urlManager->createAbsoluteUrl('uploads/projects'); // Tạo URL tuyệt đối cho thư mục lưu trữ.
            $file->mine_type = mime_content_type($this->imageFile->tempName); // Lấy loại MIME từ tệp tạm thời.
            $file->save(); // Lưu đối tượng File vào cơ sở dữ liệu.
    
            $projectImage = new ProjectImage(); // Tạo một đối tượng ProjectImage mới.
            $projectImage->project_id = $this->id; // Liên kết hình ảnh với ID dự án hiện tại.
            $projectImage->file_id = $file->id; // Liên kết hình ảnh với ID tệp đã lưu.
            $projectImage->save(); // Lưu đối tượng ProjectImage vào cơ sở dữ liệu.
    
            // Lưu tệp hình ảnh vào thư mục chỉ định.
            if (!$this->imageFile->saveAs('uploads/projects/' . $file->name)) { // Nếu không thành công trong việc lưu tệp.
                $db->transaction->rollback(); // Hoàn tác giao dịch.
            }
        });
    }
}
