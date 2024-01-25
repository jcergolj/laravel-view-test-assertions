<?php

namespace Jcergolj\LaravelViewTestAssertions;

use Exception;
use Illuminate\Support\Str;
use PHPUnit\Framework\Assert;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use Symfony\Component\DomCrawler\Crawler;

class ViewTestAssertions
{
    protected $form = null;

    public function assertViewHasForm()
    {
        return function ($selector = null, $method = null, $action = null) {
            $this->ensureResponseHasView();

            $this->selectFormElement($selector);

            if ($this->form->getNode(0) === null) {
                Assert::fail('Form element does not exists.');

                return $this;
            }

            if ($method !== null && strcasecmp($method, $this->getFormMethod()) !== 0) {
                Assert::fail('Form (action: '.$this->form->attr('action').') (method: '.$this->form->attr('method').') does not have '.$method.' method.');

                return $this;
            }

            if ($action !== null && strcasecmp($action, $this->form->attr('action')) !== 0) {
                Assert::fail('Form (action: '.$this->form->attr('action').') does not have '.$action.' action.');

                return $this;
            }

            $this->pass();

            return $this;
        };
    }

    public function assertFormHasCSRF()
    {
        return function () {
            if ($this->form->filter('input[type="hidden"][name="_token"]')->getNode(0) === null) {
                Assert::fail('Form is missing CSRF protection. Add @csrf to the view.');
            }

            $this->pass();

            return $this;
        };
    }

    public function assertFormHasSubmitButton()
    {
        return function ($type = 'submit', $value = null) {
            $findable = 'input[type="'.$type.'"]';

            if ($value !== null) {
                $findable .= '[value="'.$value.'"]';
            }

            if ($this->form->filter($findable) === null) {
                Assert::fail('Form does not have submit button.');
            }

            $this->pass();

            return $this;
        };
    }

    public function assertFormHasButtonInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('button', $name, $value);
        };
    }

    public function assertFormHasTextInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('text', $name, $value);
        };
    }

    public function assertFormHasColorInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('color', $name, $value);
        };
    }

    public function assertFormHasDateInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('date', $name, $value);
        };
    }

    public function assertFormHasDateLocalInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('datetime-local', $name, $value);
        };
    }

    public function assertFormHasEmailInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('email', $name, $value);
        };
    }

    public function assertFormHasFileInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('file', $name, $value);
        };
    }

    public function assertFormHasHiddenInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('hidden', $name, $value);
        };
    }

    public function assertFormHasImageInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('image', $name, $value);
        };
    }

    public function assertFormHasMonthInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('month', $name, $value);
        };
    }

    public function assertFormHasNumberInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('number', $name, $value);
        };
    }

    public function assertFormHasPasswordInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('password', $name, $value);
        };
    }

    public function assertFormHasRangeInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('range', $name, $value);
        };
    }

    public function assertFormHasResetInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('reset', $name, $value);
        };
    }

    public function assertFormHasSearchInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('search', $name, $value);
        };
    }

    public function assertFormHasTelInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('tel', $name, $value);
        };
    }

    public function assertFormHasTimeInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('time', $name, $value);
        };
    }

    public function assertFormHasUrlInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('url', $name, $value);
        };
    }

    public function assertFormHasWeekInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('week', $name, $value);
        };
    }

    public function assertFormHasDropdown()
    {
        return function ($name = null) {
            return $this->assertFormHasField('select', $name);
        };
    }

    public function assertFormHasCheckboxInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('checkbox', $name, $value);
        };
    }

    public function assertFormHasRadioInput()
    {
        return function ($name = null, $value = null) {
            return $this->assertFormHasField('radio', $name, $value);
        };
    }

    //id, name, selector, class = id, name, selector, class
    public function assertElementHasChild()
    {
        return function ($parentSelector, $childSelector) {
            $this->ensureResponseHasView();

            $crawler = new Crawler($this->getContent());
            $parentElement = $crawler->filter($parentSelector);

            if ($parentElement === null) {
                throw new Exception('Parent element does not exists.');
            }

            if ($parentElement->filter($childSelector)->getNode(0) === null) {
                Assert::fail('Child element '.$childSelector.' does not exists.');
            }

            $this->pass();

            return $this;
        };
    }

    public function assertFieldHasValidationErrorMsg()
    {
        return function ($errorMsg) {
            if (! Str::of($this->form->text())->contains($errorMsg)) {
                Assert::fail('Form does not have validation error text.');
            }

            $this->pass();

            return $this;
        };
    }

    public function assertFormHasField()
    {
        return function ($type, $name = null, $value = null) {
            $msg = "Form does not have $type field";
            if ($type === 'select') {
                $filterable = 'select';
            } else {
                $filterable = 'input[type="'.$type.'"]';
            }

            if ($name !== null) {
                $filterable .= '[name='.$this->escapeName($name).']';
                $msg .= " named $name.";
            }

            if ($value !== null && $type !== 'select') {
                $filterable .= '[value="'.$value.'"]';
            }

            if ($this->form->filter($filterable)->getNode(0) === null) {
                Assert::fail($msg);
            }

            $this->pass();

            return $this;
        };
    }

    public function assertFormHasTextarea()
    {
        return function ($name = null, $text = null) {
            $filterable = 'textarea';

            if ($name !== null) {
                $filterable .= '[name="'.$this->escapeName($name).'"]';
            }

            if ($this->form->filter($filterable)->getNode(0) === null) {
                Assert::fail("Form does not have textarea with name {$name}.");
            }

            if ($text === null) {
                $this->pass();

                return $this;
            }

            if ($this->form->filter($filterable)->text() !== $text) {
                Assert::fail("Form does not have textarea with {$text} text.");
            }

            $this->pass();

            return $this;
        };
    }

    protected function selectFormElement()
    {
        return function ($selector = null) {
            $crawler = new Crawler($this->getContent());
            $filterable = 'form';
            if ($selector !== null) {
                $filterable .= "[$selector]";
            }

            try {
                $this->form = $crawler->filter($filterable);
            } catch (SyntaxErrorException $e) {
                $this->form = $crawler->filter($selector);
            }

            return $this;
        };
    }

    protected function getFormMethod()
    {
        return function () {
            if ($this->form->filter('input[type="hidden"][name="_method"]')->getNode(0) !== null) {
                return $this->form->filter('input[type="hidden"][name="_method"]')->attr('value');
            }

            return $this->form->attr('method');
        };
    }

    protected function pass()
    {
        return function () {
            Assert::assertNull(null);
        };
    }

    protected function escapeName()
    {
        return function ($name) {
            if (strpos($name, '\\[') !== false && strpos($name, '\\]') !== false) {
                return $name;
            }

            return str_replace(['[', ']'], ['\\[', '\\]'], $name);
        };
    }
}
