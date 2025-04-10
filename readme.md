**Few additional assertions for testing Laravel views.**

# Why

Laravel has well established and documented way of testing requests. However, this is not the case for the views. I always felt that views in Laravel are neglected when it comes to testing, however being confident that form, submit button, and input fields are present is essential.

Granted, you can use Dusk, but it is significantly slower than regular feature tests and adding Dusk as part of the test suite is not always desired.

That's why I created this package. It is my attempt/proposal for adding a bit of TDD concept to the views too. Hope you like it.

# Installation

```bash
composer require --dev jcergolj/laravel-view-test-assertions
```

# Assertions

`assertViewHasForm(string $selector = null, string $method = null, string $action = null)`

<br/>

> **This assertion should alway be called first. Based on selector form is selected. By default first form is selected.**

`assertFormHasCSRF()`

`assertFormHasSubmitButton(string $name = null, string $value = null)`

`assertFormHasTextInput(string $name = null, string $value)`

`assertFormHasButtonInput($type = 'submit', string $name = null, string $value)`

`assertFormHasColorInput(string $name = null, string $value)`

`assertFormHasDateInput(string $name = null, string $value)`

`assertFormHasDateLocalInput(string $name = null, string $value)`

`assertFormHasEmailInput(string $name = null, string $value)`

`assertFormHasFileInput(string $name = null, string $value)`

`assertFormHasHiddenInput(string $name = null, string $value)`

`assertFormHasImageInput(string $name = null, string $value)`

`assertFormHasMonthInput(string $name = null, string $value)`

`assertFormHasNumberInput(string $name = null, string $value)`

`assertFormHasPasswordInput(string $name = null, string $value)`

`assertFormHasRangeInput(string $name = null, string $value)`

`assertFormHasResetInput(string $name = null, string $value)`

`assertFormHasSearchInput(string $name = null, string $value)`

`assertFormHasTelInput(string $name = null, string $value)`

`assertFormHasTextInput(string $name = null, string $value)`

`assertFormHasUrlInput(string $name = null, string $value)`

`assertFormHasWeekInput(string $name = null, string $value)`

`assertFormHasDropdown(string $name = null)`

`assertFormHasCheckboxInput(string $name = null, string $value = null)`

`assertFormHasRadioInput(string $name, string $value = null)`

`assertElementHasChild(string $parentSelector, string $childSelector)`

`assertFieldHasValidationErrorMsg(string $errorMsg)`

`assertFormHasField($type, $name, $value = null)`

`assertFormHasField(string $type, string $name, string $value = null)`

`assertElementHasChild(string $parentSelector, string $childSelector)`

`assertFieldHasValidationErrorMsg(string $errorMsg)`

# Example

## View

```html
// resources/welcome.blade.php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
    </head>

    <body class="antialiased">
        <form method="post" action="/users" id="first">
            <h1>Form</h1>

            @csrf
            <input type="text" name="first_name" />
            <div>The First Name must only contain letters.</div>

            <select name="age">
                <option value="5">5 Years</option>
            </select>

            <input type="submit" value="Save" />
        </form>

        <div id="parent">
            <div class="child"></div>
        </div>

        <form method="post" action="/post" id="second">
            <input type="text" name="title[]" />
        </form>

    </body>
</html>
```

## Example Test
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertViewHasForm() // first form is selected by default
            ->assertViewHasForm(null, 'post', '/users')
            ->assertFormHasField('text', 'first_name')
            ->assertFormHasRadioInput('gender')
            ->assertFormHasRadioInput('gender', 'male')
            ->assertFormHasCSRF()
            ->assertFormHasSubmitButton()
            ->assertFieldHasValidationErrorMsg(trans('validation.alpha', ['attribute' => 'First Name']))
            ->assertFormHasField('select', 'age')
            ->assertFormHasDropdown('age')
            ->assertFormHasCheckboxInput('confirm')
            ->assertFormHasCheckboxInput('confirm', 1)
            ->assertElementHasChild('select[name="age"]', 'option[value="5"]')
            ->assertElementHasChild('select[name="age"]', 'option:contains("5 Years")')
            ->assertElementHasChild('div#parent', 'div.child')
            ->selectFormElement('id="second"')
            ->assertViewHasForm(null, '/post')
            ->assertFormHasTextInput('title');

        // if multiple forms are presented on the page, personally would split assertions;
        $response->assertViewHasForm('id="second"')
            ->assertFormHasTextInput('title[]');
    }
}
```
