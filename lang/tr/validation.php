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
    'accepted_if'          => ':Attribute :other :value olduğunda kabul edilmelidir.',
    'active_url'           => ':Attribute geçerli bir URL olmalıdır.',
    'after'                => ':Attribute :date tarihinden sonraki bir tarih olmalıdır.',
    'after_or_equal'       => ':Attribute :date tarihine eşit veya sonraki bir tarih olmalıdır.',
    'alpha'                => ':Attribute sadece harflerden oluşmalıdır.',
    'alpha_dash'           => ':Attribute sadece harfler, rakamlar ve tireler ve alt çizgilerden oluşmalıdır.',
    'alpha_num'            => ':Attribute sadece harfler ve rakamlar içermelidir.',
    'array'                => ':Attribute dizi olmalıdır.',
    'ascii'                => ':Attribute yalnızca tek baytlık alfasayısal karakterler ve semboller içermelidir..',
    'before'               => ':Attribute :date tarihinden daha önceki bir tarih olmalıdır.',
    'before_or_equal'      => ':Attribute :date tarihine eşit veya önceki bir tarih olmalıdır.',
    'between'              => [
        'array'   => ':attribute :min - :max arasında nesneye sahip olmalıdır.',
        'file'    => ':attribute :min - :max arasındaki kilobayt değeri olmalıdır.',
        'numeric' => ':attribute :min - :max arasında olmalıdır.',
        'string'  => ':attribute :min - :max arasında karakterden oluşmalıdır.',
    ],
    'boolean'              => ':Attribute doğru veya yanlış değerinde olmalıdır.',
    'confirmed'            => ':Attribute tekrarı eşleşmiyor.',
    'current_password'     => 'Şifre doğru değil',
    'date'                 => ':Attribute geçerli bir tarih olmalıdır.',
    'date_format'          => ':Attribute :format biçimi ile eşleşmiyor.',
    'declined'             => ':Attribute alanı reddedilmelidir.',
    'declined_if'          => ':Attribute alanı :other :value olduğunda reddedilmelidir.',
    'decimal'              => ':Attribute :decimal ondalık haneye sahip olmalıdır.',
    'different'            => ':Attribute ile :other birbirinden farklı olmalıdır.',
    'digits'               => ':Attribute :digits rakam olmalıdır.',
    'digits_between'       => ':Attribute :min ile :max arasında rakam olmalıdır.',
    'dimensions'           => ':Attribute geçersiz görüntü boyutlarına sahip.',
    'distinct'             => ':Attribute alanı yinelenen bir değere sahip.',
    'doesnt_end_with'      => ':Attribute aşağıdakilerden biriyle bitemez: :values.',
    'doesnt_start_with'    => ':Attribute aşağıdakilerden biriyle başlamayabilir: :values.',
    'email'                => ':Attribute geçerli bir e-posta adresi olmalıdır.',
    'ends_with'            => 'The :attribute must end with one of the following: :values.',
    'enum'                 => 'Seçili :attribute geçersiz.',
    'exists'               => 'Seçili :attribute geçersiz.',
    'file'                 => ':Attribute bir dosya olmalıdır.',
    'filled'               => ':Attribute alanının bir değeri olmalıdır.',
    'gt'                   => [
        'array'   => 'The :attribute must have more than :value items.',
        'file'    => 'The :attribute must be greater than :value kilobytes.',
        'numeric' => ':Attribute alanı :value değerinden büyük olmalıdır.',
        'string'  => 'The :attribute must be greater than :value characters.',
    ],
    'gte'                  => [
        'array'   => 'The :attribute must have :value items or more.',
        'file'    => 'The :attribute must be greater than or equal :value kilobytes.',
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'string'  => 'The :attribute must be greater than or equal :value characters.',
    ],
    'image'                => ':Attribute alanı resim dosyası olmalıdır.',
    'in'                   => ':Attribute değeri geçersiz.',
    'in_array'             => ':Attribute alanı, other: içinde bulunmamaktadır.',
    'integer'              => ':Attribute rakam olmalıdır.',
    'ip'                   => ':Attribute geçerli bir IP adresi olmalıdır.',
    'ipv4'                 => ':Attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6'                 => ':Attribute geçerli bir IPv6 adresi olmalıdır.',
    'json'                 => ':Attribute geçerli bir JSON dizesi olmalıdır.',
    'lowercase'            => ':Attribute küçük harf olmalıdır.',
    'lt'                   => [
        'array'   => 'The :attribute must have less than :value items.',
        'file'    => 'The :attribute must be less than :value kilobytes.',
        'numeric' => 'The :attribute must be less than :value.',
        'string'  => 'The :attribute must be less than :value characters.',
    ],
    'lte'                  => [
        'array'   => 'The :attribute must not have more than :value items.',
        'file'    => 'The :attribute must be less than or equal :value kilobytes.',
        'numeric' => ':Attribute değeri :value değerinden küçük veya eşit olmalıdır.',
        'string'  => 'The :attribute must be less than or equal :value characters.',
    ],
    'mac_address'          => ':Attribute geçerli bir MAC adresi olmalıdır.',
    'max'                  => [
        'array'   => ':Attribute değeri :max adedinden az nesneye sahip olmalıdır.',
        'file'    => ':Attribute değeri :max kilobayt değerinden küçük olmalıdır.',
        'numeric' => ':Attribute değeri :max değerinden küçük olmalıdır.',
        'string'  => ':Attribute değeri :max karakter değerinden küçük olmalıdır.',
    ],
    'max_digits'           => ':Attribute :max rakamdan fazla olmamalıdır.',
    'mimes'                => ':Attribute dosya biçimi :values olmalıdır.',
    'mimetypes'            => ':Attribute :values türünde bir dosya olmalıdır.',
    'min'                  => [
        'array'   => ':Attribute en az :min nesneye sahip olmalıdır.',
        'file'    => ':Attribute değeri :min kilobayt değerinden büyük olmalıdır.',
        'numeric' => ':Attribute değeri :min değerinden büyük olmalıdır.',
        'string'  => ':Attribute değeri :min karakter değerinden büyük olmalıdır.',
    ],
    'min_digits'           => ':Attribute en az :min basamak içermelidir.',
    'multiple_of'          => ':Attribute :value değerinin katları olmalıdır.',
    'not_in'               => 'Seçili :attribute geçersiz.',
    'not_regex'            => 'The :attribute format is invalid.',
    'numeric'              => ':Attribute rakam olmalıdır.',
    'password' => [
        'mixed' => ':Attribute en az bir büyük ve bir küçük harf içermelidir.',
        'letters' => ':Attribute en az bir harf içermelidir.',
        'symbols' => ':Attribute en az bir sembol içermelidir.',
        'numbers' => ':Attribute en az bir sayı içermelidir.',
        'uncompromised' => 'Verilen :attribute bir veri sızıntısında göründü. Lütfen farklı bir :attribute seçin.',
    ],
    'present'              => ':Attribute alanı bulunmalıdır.',
    'prohibited'           => ':Attribute alanı yasaklanmıştır.',
    'prohibited_if'        => ':Attribute alanı :other :value olduğunda yasaklanmıştır.',
    'prohibited_unless'    => ':Attribute alanı :values içinde :other olmadığı sürece yasaklanmıştır.',
    'prohibits'            => ':attribute alanı :other değerinin bulunmasını yasaklar.',
    'regex'                => ':Attribute biçimi geçersiz.',
    'required'             => ':Attribute alanı gereklidir.',
    'required_array_keys'  => ':Attribute alanı :values değerlerini içermelidir.',
    'required_if'          => ':Attribute alanı, :other :value değerine sahip olduğunda zorunludur.',
    'required_if_accepted' => ':Attribute alanı, :other kabul edildiğinde gereklidir.',
    'required_unless'      => ':Attribute alanı :other :values içinde olmadıkça gereklidir.',
    'required_with'        => ':Attribute alanı :values varken zorunludur.',
    'required_with_all'    => ':Attribute alanı :values varken zorunludur.',
    'required_without'     => ':Attribute alanı :values yokken zorunludur.',
    'required_without_all' => ':Attribute alanı :values yokken zorunludur.',
    'same'                 => ':attribute ile :other eşleşmelidir.',
    'size'                 => [
        'array'   => ':attribute :size nesneye sahip olmalıdır.',
        'file'    => ':attribute :size kilobyte olmalıdır.',
        'numeric' => ':attribute :size olmalıdır.',
        'string'  => ':attribute :size karakter olmalıdır.',
    ],
    'starts_with'          => ':Attribute aşağıdakilerden biriyle başlamalıdır: :values.',
    'string'               => ':Attribute alanı bir dizge olmalıdır.',
    'timezone'             => ':Attribute geçerli bölge olmalıdır.',
    'unique'               => ':Attribute daha önceden kayıt edilmiş.',
    'uploaded'             => ':Attribute yüklenirken hata oluştu.',
    'uppercase'            => ':Attribute büyük harf olmalıdır.',
    'url'                  => ':Attribute biçimi geçersiz.',
    'ulid'                 => ':Attribute geçerli bir ULID olmalıdır.',
    'uuid'                 => ':Attribute geçerli bir UUID olmalıdır.',

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
