<?php

namespace tests\eLife\Patterns\ViewModel;

use eLife\Patterns\ViewModel\FormLabel;
use eLife\Patterns\ViewModel\MessageGroup;
use eLife\Patterns\ViewModel\TextField;
use InvalidArgumentException;

final class TextFieldTest extends ViewModelTest
{
    /**
     * @test
     */
    public function it_has_data()
    {
        $data = [
            'inputType' => 'email',
            'label' => [
                'labelText' => 'label',
                'isVisuallyHidden' => false,
            ],
            'name' => 'someName',
            'id' => 'id',
            'placeholder' => 'placeholder',
            'required' => true,
            'disabled' => true,
            'autofocus' => true,
            'value' => 'value',
            'state' => 'invalid',
            'messageGroup' => [
                'errorText' => 'error text',
                'infoText' => 'info text',
            ],
        ];
        $textField = TextField::emailInput(
            new FormLabel($data['label']['labelText']),
            $data['id'],
            $data['name'],
            $data['placeholder'],
            $data['required'],
            $data['disabled'],
            $data['autofocus'],
            $data['value'],
            TextField::STATE_INVALID,
            MessageGroup::forInfoText($data['messageGroup']['infoText'], $data['messageGroup']['errorText'])

        );

        $this->assertSame($data['name'], $textField['name']);
        $this->assertSame($data['id'], $textField['id']);
        $this->assertSame($data['label'], $textField['label']->toArray());
        $this->assertSame($data['placeholder'], $textField['placeholder']);
        $this->assertSame($data['required'], $textField['required']);
        $this->assertSame($data['disabled'], $textField['disabled']);
        $this->assertSame($data['autofocus'], $textField['autofocus']);
        $this->assertSame($data['value'], $textField['value']);
        $this->assertSame($data['state'], $textField['state']);

        // id of messageGroup is unpredictable so must be ignored by the test
        $textFieldAsArray = $textField->toArray();
        unset($textFieldAsArray['messageGroup']['id']);
        $this->assertSame($data['messageGroup'], $textFieldAsArray['messageGroup']);
        $this->assertSame($data, $textFieldAsArray);
    }

    /**
     * @test
     */
    public function it_must_have_a_message_group_when_in_error_state()
    {
        $this->expectException(InvalidArgumentException::class);

        TextField::textInput(new FormLabel('label'), 'identifier', 'identifier', 'placeholder', true, false, false, 'value', TextField::STATE_INVALID, null);
    }

    /**
     * @test
     */
    public function its_message_group_must_have_an_error_message_when_in_error_state()
    {
        $this->expectException(InvalidArgumentException::class);

        TextField::textInput(new FormLabel('label'), 'identifier', 'identifier', 'placeholder', true, false, false, 'value', TextField::STATE_INVALID, MessageGroup::forInfoText('info text'));
    }

    public function viewModelProvider() : array
    {
        return [
            'minimal email input' => [TextField::emailInput(new FormLabel('label'), 'id', 'some name')],
            'complete email input' => [TextField::emailInput(new FormLabel('label'), 'id', 'some name', 'placeholder', true, true, true, 'value', TextField::STATE_INVALID, MessageGroup::forErrorText('error message'))],
            'minimal password input' => [TextField::passwordInput(new FormLabel('label'), 'id', 'some name')],
            'complete password input' => [TextField::passwordInput(new FormLabel('label'), 'id', 'some name', 'placeholder', true, true, true, 'value', TextField::STATE_INVALID, MessageGroup::forErrorText('error message'))],
            'minimal search input' => [TextField::searchInput(new FormLabel('label'), 'id', 'some name')],
            'complete search input' => [TextField::searchInput(new FormLabel('label'), 'id', 'some name', 'placeholder', true, true, true, 'value', TextField::STATE_INVALID, MessageGroup::forErrorText('error message'))],
            'minimal tel input' => [TextField::telInput(new FormLabel('label'), 'id', 'some name')],
            'complete tel input' => [TextField::telInput(new FormLabel('label'), 'id', 'some name', 'placeholder', true, true, true, 'value', TextField::STATE_INVALID, MessageGroup::forErrorText('error message'))],
            'minimal text input' => [TextField::textInput(new FormLabel('label'), 'id', 'some name')],
            'complete text input' => [TextField::textInput(new FormLabel('label'), 'id', 'some name', 'placeholder', true, true, true, 'value', TextField::STATE_INVALID, MessageGroup::forErrorText('error message'))],
            'minimal url input' => [TextField::urlInput(new FormLabel('label'), 'id', 'some name')],
            'complete url input' => [TextField::urlInput(new FormLabel('label'), 'id', 'some name', 'placeholder', true, true, true, 'value', TextField::STATE_INVALID, MessageGroup::forErrorText('error message'))],
        ];
    }

    protected function expectedTemplate() : string
    {
        return 'resources/templates/text-field.mustache';
    }
}
