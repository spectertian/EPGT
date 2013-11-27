<?php

namespace Model\Document;

/**
 * Events document.
 */
class Events extends \Model\Document\Base\Events
{
    protected $events = array();

    public function getEvents()
    {
        return $this->events;
    }

    public function clearEvents()
    {
        $this->events = array();
    }

    /*
     * Events.
     */
    public function preInsert()
    {
        $this->events[] = 'preInsert';
    }

    public function postInsert()
    {
        $this->events[] = 'postInsert';
    }

    public function preUpdate()
    {
        $this->events[] = 'preUpdate';
    }

    public function postUpdate()
    {
        $this->events[] = 'postUpdate';
    }

    public function preSave()
    {
        $this->events[] = 'preSave';
    }

    public function postSave()
    {
        $this->events[] = 'postSave';
    }

    public function preDelete()
    {
        $this->events[] = 'preDelete';
    }

    public function postDelete()
    {
        $this->events[] = 'postDelete';
    }

    /*
     * Extensions events.
     */
    public function preInsertExtensions()
    {
        $this->events[] = 'preInsertExtensions';
    }

    public function postInsertExtensions()
    {
        $this->events[] = 'postInsertExtensions';
    }

    public function preUpdateExtensions()
    {
        $this->events[] = 'preUpdateExtensions';
    }

    public function postUpdateExtensions()
    {
        $this->events[] = 'postUpdateExtensions';
    }

    public function preSaveExtensions()
    {
        $this->events[] = 'preSaveExtensions';
    }

    public function postSaveExtensions()
    {
        $this->events[] = 'postSaveExtensions';
    }

    public function preDeleteExtensions()
    {
        $this->events[] = 'preDeleteExtensions';
    }

    public function postDeleteExtensions()
    {
        $this->events[] = 'postDeleteExtensions';
    }
}
