<?php
namespace HeraldOfArms\Herald;

use Herald\Contracts\NotificationInterface;

/**
 * Base notification class.
 */
class Notification implements NotificationInterface
{
    CONST LABEL_DEFAULT = "#DSSA";
    CONST LABEL_PRIMARY = "#DSSA";
    CONST LABEL_SUCCESS = "#DSSA";
    CONST LABEL_INFO = "#DSSA";
    CONST LABEL_WARNING = "#DSSA";
    CONST LABEL_DANGER = "#DSSA";

    private $data = array();

    private $attachments = array();

    private $subject;

    private $body;

    private $label;

    /**
     * Constructor
     *
     * @param        $body
     * @param array  $data
     */
    public function __construct($body, array $data = [])
    {
        $this->body    = $body;
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the message transported by this notification.
     *
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getParameter($parameter)
    {
        if (Arr::has($this->parameters, $parameter)) {
            return $this->parameters[$parameter];
        }
    }

    /**
     * @inheritDoc
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @inheritDoc
     */
    public function overwriteParameters($paramenters)
    {
        $this->parameters = array_merge($this->parameters, $paramenters);

        return $this;
    }
}