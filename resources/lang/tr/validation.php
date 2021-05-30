<?php

use App\Enums\TransactionType;

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':Attribute kabul edilmelidir.',
    'active_url'           => ':Attribute geçerli bir URL olmalıdır.',
    'after'                => ':Attribute :date tarihinden sonraki bir tarih olmalıdır.',
    'after_or_equal'       => ':Attribute :date tarihine eşit veya sonraki bir tarih olmalıdır.',
    'alpha'                => ':Attribute sadece harflerden oluşmalıdır.',
    'alpha_dash'           => ':Attribute sadece harfler, rakamlar ve tireler ve alt çizgilerden oluşmalıdır.',
    'alpha_num'            => ':Attribute sadece harfler ve rakamlar içermelidir.',
    'array'                => ':Attribute dizi olmalıdır.',
    'before'               => ':Attribute :date tarihinden daha önceki bir tarih olmalıdır.',
    'before_or_equal'      => ':Attribute :date tarihine eşit veya önceki bir tarih olmalıdır.',
    'between'              => [
        'numeric' => ':attribute :min - :max arasında olmalıdır.',
        'file'    => ':attribute :min - :max arasındaki kilobayt değeri olmalıdır.',
        'string'  => ':attribute :min - :max arasında karakterden oluşmalıdır.',
        'array'   => ':attribute :min - :max arasında nesneye sahip olmalıdır.',
    ],
    'boolean'              => ':Attribute doğru veya yanlış değerinde olmalıdır.',
    'confirmed'            => ':Attribute tekrarı eşleşmiyor.',
    'date'                 => ':Attribute geçerli bir tarih olmalıdır.',
    'date_format'          => ':Attribute :format biçimi ile eşleşmiyor.',
    'different'            => ':Attribute ile :other birbirinden farklı olmalıdır.',
    'digits'               => ':Attribute :digits rakam olmalıdır.',
    'digits_between'       => ':Attribute :min ile :max arasında rakam olmalıdır.',
    'dimensions'           => ':Attribute geçersiz görüntü boyutlarına sahip.',
    'distinct'             => ':Attribute alanı yinelenen bir değere sahip.',
    'email'                => ':Attribute geçerli bir e-posta adresi olmalıdır.',
    'ends_with'            => 'The :attribute must end with one of the following: :values.',
    'exists'               => 'Seçili :attribute geçersiz.',
    'file'                 => ':Attribute bir dosya olmalıdır.',
    'filled'               => ':Attribute alanının bir değeri olmalıdır.',
    'gt'                   => [
        'numeric' => ':Attribute alanı :value değerinden büyük olmalıdır.',
        'file'    => 'The :attribute must be greater than :value kilobytes.',
        'string'  => 'The :attribute must be greater than :value characters.',
        'array'   => 'The :attribute must have more than :value items.',
    ],
    'gte'                  => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file'    => 'The :attribute must be greater than or equal :value kilobytes.',
        'string'  => 'The :attribute must be greater than or equal :value characters.',
        'array'   => 'The :attribute must have :value items or more.',
    ],
    'image'                => ':Attribute alanı resim dosyası olmalıdır.',
    'in'                   => ':Attribute değeri geçersiz.',
    'in_array'             => ':Attribute alanı, other: içinde bulunmamaktadır.',
    'integer'              => ':Attribute rakam olmalıdır.',
    'ip'                   => ':Attribute geçerli bir IP adresi olmalıdır.',
    'ipv4'                 => ':Attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6'                 => ':Attribute geçerli bir IPv6 adresi olmalıdır.',
    'json'                 => ':Attribute geçerli bir JSON dizesi olmalıdır.',
    'lt'                   => [
        'numeric' => 'The :attribute must be less than :value.',
        'file'    => 'The :attribute must be less than :value kilobytes.',
        'string'  => 'The :attribute must be less than :value characters.',
        'array'   => 'The :attribute must have less than :value items.',
    ],
    'lte'                  => [
        'numeric' => ':Attribute değeri :value değerinden küçük veya eşit olmalıdır.',
        'file'    => 'The :attribute must be less than or equal :value kilobytes.',
        'string'  => 'The :attribute must be less than or equal :value characters.',
        'array'   => 'The :attribute must not have more than :value items.',
    ],
    'max'                  => [
        'numeric' => ':Attribute değeri :max değerinden küçük olmalıdır.',
        'file'    => ':Attribute değeri :max kilobayt değerinden küçük olmalıdır.',
        'string'  => ':Attribute değeri :max karakter değerinden küçük olmalıdır.',
        'array'   => ':Attribute değeri :max adedinden az nesneye sahip olmalıdır.',
    ],
    'mimes'                => ':Attribute dosya biçimi :values olmalıdır.',
    'mimetypes'            => ':Attribute :values türünde bir dosya olmalıdır.',
    'min'                  => [
        'numeric' => ':Attribute değeri :min değerinden büyük olmalıdır.',
        'file'    => ':Attribute değeri :min kilobayt değerinden büyük olmalıdır.',
        'string'  => ':Attribute değeri :min karakter değerinden büyük olmalıdır.',
        'array'   => ':Attribute en az :min nesneye sahip olmalıdır.',
    ],
    'multiple_of'          => ':Attribute :value değerinin katları olmalıdır.',
    'not_in'               => 'Seçili :attribute geçersiz.',
    'not_regex'            => 'The :attribute format is invalid.',
    'password'             => 'The password is incorrect.',
    'numeric'              => ':Attribute rakam olmalıdır.',
    'present'              => ':Attribute alanı bulunmalıdır.',
    'regex'                => ':Attribute biçimi geçersiz.',
    'required'             => ':Attribute alanı gereklidir.',
    'required_if'          => ':Attribute alanı, :other :value değerine sahip olduğunda zorunludur.',
    'required_unless'      => ':Attribute alanı :other :values içinde olmadıkça gereklidir.',
    'required_with'        => ':attribute alanı :values varken zorunludur.',
    'required_with_all'    => ':attribute alanı :values varken zorunludur.',
    'required_without'     => ':attribute alanı :values yokken zorunludur.',
    'required_without_all' => ':attribute alanı :values yokken zorunludur.',
    'same'                 => ':attribute ile :other eşleşmelidir.',
    'size'                 => [
        'numeric' => ':attribute :size olmalıdır.',
        'file'    => ':attribute :size kilobyte olmalıdır.',
        'string'  => ':attribute :size karakter olmalıdır.',
        'array'   => ':attribute :size nesneye sahip olmalıdır.',
    ],
    'starts_with'          => 'The :attribute must start with one of the following: :values.',
    'string'               => ':Attribute alanı bir dizge olmalıdır.',
    'timezone'             => ':Attribute geçerli bölge olmalıdır.',
    'unique'               => ':Attribute daha önceden kayıt edilmiş.',
    'uploaded'             => ':Attribute yüklenirken hata oluştu.',
    'url'                  => ':Attribute biçimi geçersiz.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention 'attribute.rule' to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of 'email'. This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'email'         => 'E-Posta Adresi',
        'password'      => 'Şifre',
        'symbol_id'     => 'Hisse',
        'share_id'      => 'Hisse',
        'type'          => 'İşlem',
        'date_at'       => 'Tarih',
        'lot'           => 'Lot',
        'price'         => 'Fiyat',
        'name'          => 'Portföy Adı',
        'currency'      => 'Para Birimi',
        'commission'    => 'Komisyon',
        'dividend_gain' => 'Temettü Kazancı',
        'preference'    => 'Rüçhan',
    ],

    'values' => [
        'type' => [
            TransactionType::Buying         => 'Alım',
            TransactionType::Sale           => 'Satım',
            TransactionType::Dividend       => 'Temettü',
            TransactionType::Bonus          => 'Bedelsiz',
            TransactionType::Rights         => 'Bedelli',
            TransactionType::MergerOut      => 'Birleşme Çıkış',
            TransactionType::MergerIn       => 'Birleşme Giriş',
            TransactionType::PublicOffering => 'Halka Arz',
        ],
    ],

    'money_gt' => ':attribute alanı :value tutarından büyük olmalıdır.',
];
