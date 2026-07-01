<?php

namespace KrubiK\Enums;

/**
 * مدیریت انواع دکمه‌ها با قدرت PHP 8.1+ Enums
 * حذف کامل رشته‌های جادویی و جلوگیری از خطاهای تایپی
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
**/
enum ButtonType: string
{
    // انواع استاندارد
    case Text = 'Text';
    case Link = 'Link';
    case Simple = 'Button'; // نگاشت به مقدار استاندارد متد قدیمی
    
    // انواع انتخابی و ورودی
    case Selection = 'Selection';
    case NumberPicker = 'NumberPicker';
    case StringPicker = 'StringPicker';
    case Calendar = 'Calendar';
    case LocationPicker = 'Location'; // نام‌گذاری صریح‌تر
    case TextBox = 'TextBox';

    // انواع مدیا و فایل
    case Payment = 'Payment';
    case CameraImage = 'CameraImage';
    case CameraVideo = 'CameraVideo';
    case GalleryImage = 'GalleryImage';
    case GalleryVideo = 'GalleryVideo';
    case File = 'File';
    case Audio = 'Audio';
    case RecordAudio = 'RecordAudio';
    
    // انواع شخصی و تعاملی
    case MyPhoneNumber = 'MyPhoneNumber';
    case MyLocation = 'MyLocation';
    case ActivityPhoneNumber = 'ActivityPhoneNumber';
    case AsMLocation = 'AsMLocation';
    case Barcode = 'Barcode';

    /**
     * آیا این دکمه نیاز به دیتای اضافی (Payload) دارد؟
     * (جهت ولیدیشن هوشمند در آینده)
     */
    public function requiresPayload(): bool
    {
        return match($this) {
            self::Selection, self::NumberPicker, self::StringPicker, 
            self::Calendar, self::LocationPicker, self::TextBox => true,
            default => false,
        };
    }
}
