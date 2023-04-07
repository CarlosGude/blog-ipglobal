<?php


namespace App\Message;


class EntityEvent
{
    public const EVENT_CREATE = 'create';
    public const EVENT_UPDATE = 'update';

    public function __construct(
        public string $class,
        public string $event,
        public string $uuid,
        public array $changeFields = array()
    )
    {
    }

    public function hasChangeField(string $field): bool
    {
        return array_key_exists($field,$this->changeFields);
    }

    public function getNewValue(string $field): mixed
    {
        if(!$this->hasChangeField($field)){
            return null;
        }

        return $this->changeFields[$field][1];
    }

    public function getOldValue(string $field): mixed
    {
        if(!$this->hasChangeField($field)){
            return null;
        }

        return $this->changeFields[$field][0];
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @return array
     */
    public function getChangeFields(): array
    {
        return $this->changeFields;
    }



}