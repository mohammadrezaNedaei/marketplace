<?php

return [

    'required'  => 'فیلد :attribute الزامی است.',
    'string'    => 'فیلد :attribute باید پر شده باشد.',
    'numeric'   => 'فیلد :attribute باید عدد باشد.',
    'integer'   => 'فیلد :attribute باید عدد صحیح باشد.',
    'regex' => 'فرمت :attribute صحیح نیست.',
    'min' => [
        'string'  => 'فیلد :attribute باید حداقل :min کاراکتر باشد.',
        'numeric' => 'فیلد :attribute باید حداقل :min باشد.',
    ],
    'max' => [
        'string'  => 'فیلد :attribute نباید بیشتر از :max کاراکتر باشد.',
        'numeric' => 'فیلد :attribute نباید بیشتر از :max باشد.',
        'file'    => 'حجم فایل :attribute نباید بیشتر از :max کیلوبایت باشد.',
    ],
    'unique'    => 'این :attribute قبلاً استفاده شده است.',
    'confirmed' => 'تکرار :attribute مطابقت ندارد.',
    'in'        => 'مقدار انتخاب‌شده برای :attribute نامعتبر است.',
    'exists'    => ':attribute انتخاب‌شده معتبر نیست.',
    'image'     => 'فیلد :attribute باید تصویر باشد.',
    'mimes'     => 'فرمت فایل :attribute باید یکی از این‌ها باشد: :values.',
    'file'      => 'فیلد :attribute باید یک فایل باشد.',
    'lt' => [
        'numeric' => 'فیلد :attribute باید کمتر از :value باشد.',
    ],
    'nullable'  => '',

    'attributes' => [
        'username'        => 'نام کاربری',
        'phone'           => 'شماره موبایل',
        'password'        => 'رمز عبور',
        'current_password'=> 'رمز عبور فعلی',
        'role'            => 'نقش',
        'title'           => 'عنوان',
        'description'     => 'توضیحات',
        'price'           => 'قیمت',
        'discount_price'  => 'قیمت با تخفیف',
        'category_id'     => 'دسته‌بندی',
        'picture'         => 'عکس',
        'file'            => 'فایل',
        'subject'         => 'موضوع',
        'message'         => 'پیام',
        'comment'         => 'نظر',
        'rating'          => 'امتیاز',
        'status'          => 'وضعیت',
    ],

];
