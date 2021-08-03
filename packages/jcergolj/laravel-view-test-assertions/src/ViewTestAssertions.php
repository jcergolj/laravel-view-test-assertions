<?php

namespace Jcergolj\LaravelViewTestAssertions;

use Exception;
use Illuminate\Support\Str;
use PHPUnit\Framework\Assert;

include('simple_html_dom.php');

class ViewTestAssertions
{
    public function assertViewHasForm()
    {
        return function ($method = null, $action = null) {

            $this->ensureResponseHasView();

            $html = str_get_html($this->getContent());

            $form = $html->find('form', 0);

            Assert::assertNotEmpty($form, 'From element does not exists.');

            if ($method !== null) {
                Assert::assertSame(0, strcasecmp($method, $form->method), 'Form does not have '.$method.' method.');
            }

            if ($action !== null) {
                Assert::assertSame(0, strcasecmp($action, $form->action), 'Form does not have '.$method.' action.');
            }

            return $this;
        };
    }

    public function assertFormHasCSRF()
    {
        return function () {
            $form = $this->getFormElement();

            Assert::assertNotNull($form->find('input[type="hidden"][name="_token"]', 0), 'Form is missing CSRF protection. Add @csrf to the view.');

            return $this;
        };
    }

    public function assertFormHasSubmitButton()
    {
        return function ($type = 'submit', $text = null) {
            $this->assertFormHasField($type);

            if ($text !== null) {
                $form = $this->getFormElement();
                Assert::assertNotNull($form->find('input[type="'.$type.'"][value="'.$text.'"]', 0), 'Form does not have submit button.');
            }

            return $this;
        };
    }

    public function assertFormHasField()
    {
        return function ($type, $name = null) {
            $form = $this->getFormElement();

            $findable = '(input[type="'.$type.'"]';

            if ($name !== null) {
                $findable .='[name='.$name.'])';
            } else {
                $findable .=')';
            }

            Assert::assertNotNull($form->find($findable, 0), 'Form does not have '. $type .' field.');

            return $this;
        };
    }

    // public function assertFormHasDropdown()
    // {
    //     return function ($name = null) {
    //         $form = $this->getFormElement();

    //         $findable = 'select';

    //         if ($name !== null) {
    //             $findable .='[name='.$name.']';
    //         }

    //         Assert::assertNotNull($form->find($findable, 0), 'Form does not have dropdown field.');

    //         return $this;
    //     };
    // }

    //id, name, selector, class = id, name, selector, class
    public function elementHasChild()
    {
        return function ($parent, $child) {
            $form = $this->getFormElement();

            Assert::assertTrue(Str::of($form->innerText)->contains($errorMsg), 'Form does not have validation error text.');

            return $this;
        };
    }

    public function assertFieldHasValidationErrorMsg()
    {
        return function ($errorMsg) {
            $form = $this->getFormElement();

            Assert::assertTrue(Str::of($form->innerText)->contains($errorMsg), 'Form does not have validation error text.');

            return $this;
        };
    }

    protected function getFormElement()
    {
        return function () {
            $this->ensureResponseHasView();

            $html = str_get_html($this->getContent());

            $form = $html->find('form', 0);

            if ($form === null) {
                throw new Exception('Form does not exists.');
            }

            return $form;
        };
    }
}
