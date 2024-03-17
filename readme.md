# Akatekno ID Attachable

This is a simple package to implement multiple attachments in a model. This package is created based on my annoyance for creating the same model and migrations over and over. So this is why I made the Attachable package.


## Installation

In your Laravel application, run `composer require akatekno/attachable`, then run `php artisan migrate` to create the `attachments` table.


## Usage

There are currently two types of the Attachable available:
1. `AttachableOne` if you want to implement only ONE attachment per-model. The case example is the User's profile picture, or the User's identity card.
2. `AttachableMany` if you want to implement many attachments per-model. The case example is a Blog's gallery picture, or a Report's attachments.


### `AttachableOne`

The implementation of the code is fairly simple:

1. Implement the interface `AttachableOne` and trait `AttachableOne`
```php
<?php

namespace App\Models;

use Akatekno\Attachable\Interfaces\AttachableOne;
use Akatekno\Attachable\Traits\AttachableOne as TraitsAttachableOne;

class User implements AttachableOne
{
    use TraitsAttachableOne;

    // ... the rest of the code
}
```

2. To get the attachment data, use the example below:

```php
<?php

use App\Models\User;

class Controller
{
    public function index()
    {
        return User::first()->attachment;
    }
}
```

This will return the data below:

```json
{
    "id": "9b94845b-c357-4248-93cf-8ce10d7c249f",
    "attachable_type": "App\\Models\\User",
    "attachable_id": 1,
    "name": "pdf-example.pdf",
    "path": "2024-03-16/Ifd5GkgoyAvcLihEMz7i7SSLC46rEj2aFO9HGJwz.pdf",
    "mime_type": "application/pdf",
    "extension": "pdf",
    "size": 36115,
    "type": null,
    "created_at": "2024-03-16T20:43:00.000000Z",
    "updated_at": "2024-03-16T20:43:00.000000Z",
    "deleted_at": null
}
```

3. To store the data to the attachment, use the example below:

```php
<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::post('attach', function (Request $request) {
    $file = $request->file('file');

    $user = User::first();

    $user->attachments()->create([
        'name' => $file->getClientOriginalName(),
        'path' => $file->store(date('Y-m-d')),
        'mime_type' => $file->getClientMimeType(),
        'extension' => $file->getClientOriginalExtension(),
        'size' => $file->getSize(),
        'type' => null,
    ]);
});
```


### `AttachableMany`

This implementation is more like the same as `AttachableOne`, the difference is the plural usage of the `attachment` to `attachments`.


1. Implement the interface `AttachableMany` and trait `AttachableMany`
```php
<?php

namespace App\Models;

use Akatekno\Attachable\Interfaces\AttachableMany;
use Akatekno\Attachable\Traits\AttachableMany as TraitsAttachableMany;

class User implements AttachableMany
{
    use TraitsAttachableMany;

    // ... the rest of the code
}
```

2. To get the attachments data, use the example below:

```php
<?php

use App\Models\User;

class Controller
{
    public function index()
    {
        return User::first()->attachments;
    }
}
```

This will return the data below:

```json
[
    {
        "id": "9b94845b-c357-4248-93cf-8ce10d7c249f",
        "attachable_type": "App\\Models\\User",
        "attachable_id": 1,
        "name": "pdf-example.pdf",
        "path": "2024-03-16/Ifd5GkgoyAvcLihEMz7i7SSLC46rEj2aFO9HGJwz.pdf",
        "mime_type": "application/pdf",
        "extension": "pdf",
        "size": 36115,
        "type": null,
        "created_at": "2024-03-16T20:43:00.000000Z",
        "updated_at": "2024-03-16T20:43:00.000000Z",
        "deleted_at": null
    },
    {
        "id": "9b948473-7ea7-40b1-b83e-253d30913eea",
        "attachable_type": "App\\Models\\User",
        "attachable_id": 1,
        "name": "pdf-example.pdf",
        "path": "2024-03-16/69HDlwDFPZ5kKa7OTO3x2OfSeejx3gkHXwFzqBmC.pdf",
        "mime_type": "application/pdf",
        "extension": "pdf",
        "size": 36115,
        "type": null,
        "created_at": "2024-03-16T20:43:16.000000Z",
        "updated_at": "2024-03-16T20:43:16.000000Z",
        "deleted_at": null
    }
]
```

3. To store the data to the attachment, use the example below:

```php
<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::post('attach', function (Request $request) {
    $files = $request->allFiles();

    $user = User::first();

    /** @var \Illuminate\Http\UploadedFile $file */
    foreach ($files as $file) {
        $user->attachments()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $file->store(date('Y-m-d')),
            'mime_type' => $file->getClientMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'type' => null,
        ]);
    }
});
```

## Custom Relation Name

Because this package is simplifying the `MorphOne` or `MorphMany` code usage, you can always modify the relation name to your liking such as:

```php
<?php

namespace App\Models;

use Akatekno\Attachable\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Reservation extends Model
{
    // ...

    public function visit_request_letter(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachable')
            ->where('type', 'visit-request-letter');
    }

    public function hotel_booking_invoice(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachable')
            ->where('type', 'hotel-booking-invoice');
    }

    public function other_attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable')
            ->where('type', 'other-attachment');
    }

    // ...
}
```

Now, you have 3 different attachment type in the same `attachment`  table. To get the data in each of the relation by example above, just call the `$reservation->visit_request_letter`, `$reservation->hotel_booking_invoice`, or `$reservation->other_attachments`.

To attach the file, simply add specify the `type` of the column.

```php
<?php

// Visit Request Letter
$reservation->visit_request_letter()->create([
    'name' => $file->getClientOriginalName(),
    'path' => $file->store(date('Y-m-d')),
    'mime_type' => $file->getClientMimeType(),
    'extension' => $file->getClientOriginalExtension(),
    'size' => $file->getSize(),
    'type' => 'visit-request-letter',
]);

// Hotel Booking Invoice
$reservation->hotel_booking_invoice()->create([
    'name' => $file->getClientOriginalName(),
    'path' => $file->store(date('Y-m-d')),
    'mime_type' => $file->getClientMimeType(),
    'extension' => $file->getClientOriginalExtension(),
    'size' => $file->getSize(),
    'type' => 'hotel-booking-invoice',
]);

// Other Attachments
$reservation->other_attachments()->create([
    'name' => $file->getClientOriginalName(),
    'path' => $file->store(date('Y-m-d')),
    'mime_type' => $file->getClientMimeType(),
    'extension' => $file->getClientOriginalExtension(),
    'size' => $file->getSize(),
    'type' => 'other-attachment',
]);
```


## To-dos

We need somebody who willing to spend some time to fix these issues:

- The table name is fixed as `attachments`, if you're having the same table name, this might broke the your app.
- The current supported morphable is using UUID as its Primary Key, we need more broad types of IDs such ULID and Incrementing ID.

After the above issue is fixed, we will make a configuration file so you could fine-tune the package for your liking.