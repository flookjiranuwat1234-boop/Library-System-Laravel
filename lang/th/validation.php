<?php

return [
    'accepted' => 'ต้องยอมรับ :attribute',
    'confirmed' => 'การยืนยัน :attribute ไม่ตรงกัน',
    'current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง',
    'email' => ':attribute ต้องเป็นอีเมลที่ถูกต้อง',
    'exists' => ':attribute ที่เลือกไม่ถูกต้อง',
    'integer' => ':attribute ต้องเป็นจำนวนเต็ม',
    'lowercase' => ':attribute ต้องเป็นตัวพิมพ์เล็ก',
    'max' => [
        'numeric' => ':attribute ต้องไม่มากกว่า :max',
        'string' => ':attribute ต้องยาวไม่เกิน :max ตัวอักษร',
    ],
    'min' => [
        'numeric' => ':attribute ต้องไม่น้อยกว่า :min',
        'string' => ':attribute ต้องมีอย่างน้อย :min ตัวอักษร',
    ],
    'numeric' => ':attribute ต้องเป็นตัวเลข',
    'password' => [
        'letters' => ':attribute ต้องมีตัวอักษรอย่างน้อยหนึ่งตัว',
        'mixed' => ':attribute ต้องมีทั้งตัวพิมพ์ใหญ่และตัวพิมพ์เล็ก',
        'numbers' => ':attribute ต้องมีตัวเลขอย่างน้อยหนึ่งตัว',
        'symbols' => ':attribute ต้องมีสัญลักษณ์อย่างน้อยหนึ่งตัว',
        'uncompromised' => ':attribute นี้ปรากฏในข้อมูลรหัสผ่านที่รั่วไหล กรุณาเลือกรหัสผ่านอื่น',
    ],
    'regex' => 'รูปแบบ :attribute ไม่ถูกต้อง',
    'required' => 'กรุณากรอก :attribute',
    'string' => ':attribute ต้องเป็นข้อความ',
    'unique' => ':attribute นี้ถูกใช้งานแล้ว',
    'url' => ':attribute ต้องเป็น URL ที่ถูกต้อง',

    'attributes' => [
        'author' => 'ชื่อผู้แต่ง',
        'book_id' => 'หนังสือ',
        'category_id' => 'หมวดหมู่',
        'cover_image' => 'URL รูปปก',
        'current_password' => 'รหัสผ่านปัจจุบัน',
        'description' => 'รายละเอียด',
        'email' => 'อีเมล',
        'name' => 'ชื่อ',
        'password' => 'รหัสผ่าน',
        'stock' => 'จำนวนคงเหลือ',
        'title' => 'ชื่อหนังสือ',
    ],
];
