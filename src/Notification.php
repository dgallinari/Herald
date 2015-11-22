<?php
namespace HeraldOfArms\Herald;

use HeraldOfArms\Contracts\NotificationInterface;
use Html2Text\Html2Text;
use League\HTMLToMarkdown\HtmlConverter;

/**
 * Base notification class.
 */
class Notification implements NotificationInterface
{
    protected $data = [];

    protected $parameters = [];

    protected $message;

    protected $config;

    /**
     * Constructor
     *
     * @param        $message    The content of the message
     * @param array  $data       The data the should replace tokens at the $message
     * @param array  $parameters Optional paramethers to allow customization
     */
    public function __construct($message, array $data = [], array $parameters = [])
    {
        $this->message    = $message;
        $this->data       = $data;
        $this->parameters = $parameters;
    }

    /**
     * @inheritDoc
     */
    public function getMessage($type = 'original')
    {
        switch ($type) {
            case 'markdown':
                $message = $this->getMessageAsMarkdown();
                break;
            case 'plain':
                $message = $this->getMessageAsPlainText();
                break;
            default:
                $message = $this->message;
                break;
        }

        return $this->tokenReplace($message, $this->data);
    }

    /**
     * Sets the message transported by this notification.
     *
     * @param string $message
     *
     * @return $this
     */
    public function message($message)
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

        return null;

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
    public function merge($paramenters)
    {
        $this->parameters = array_merge($this->parameters, $paramenters);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getMessageAsMarkdown(array $config = [])
    {
        $converter = new HtmlConverter($config);

        return $converter->convert($this->message);
    }

    public function getMessageAsPlainText(array $config = [])
    {
        if (empty($config)) {
            $config = [
                      'do_links' => 'none',
            ];
        }

        $html = new Html2Text($this->message, $config);

        return $html->getText();
    }

    private function tokenReplace($text, array $data = [])
    {
        $text_tokens = $this->tokenScan($text);
        if (empty($text_tokens)) {
            return $text;
        }

        $replacements = [];

        foreach ($text_tokens as $token) {
            $token_name           = str_replace([']', '['], '', $token);
            $replacements[$token] = Arr::get($data, $token_name);
        }

        $tokens = array_keys($replacements);
        $values = array_values($replacements);

        return str_replace($tokens, $values, $text);
    }

    private function tokenScan($text)
    {
        // Matches tokens with the following pattern: [$token] OR [$var:token]
        preg_match_all("/\[[^\]]*\]/", $text, $tokens);
        $tokens = $tokens[0];

        return $tokens;
    }
}