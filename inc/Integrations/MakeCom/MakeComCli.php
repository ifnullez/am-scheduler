<?php

namespace AM\Scheduler\Integrations\MakeCom;

class MakeComCli
{
    protected string $env_type;
    protected string $endpoint;
    protected array $body;

    public function __construct(string $endpoint, array $body)
    {
        // endpoint to send data to
        $this->endpoint = $endpoint;

        // get env type
        $this->env_type = wp_get_environment_type();

        // add env type automatically to the body
        $this->body["environment"] = $this->env_type;

        // extract passed body array to the existing body array
        $this->body = [...$this->body, ...$body];
    }

    // make request after filling all data
    public function sendRequest(): void
    {
        if (!empty($this->body["message"]) && !empty($this->endpoint)) {
            try {
                $response = wp_remote_post($this->endpoint, [
                    "body" => $this->body,
                ]);
            } catch (\Exception $e) {
                error_log(print_r($e->getMessage(), true));
            }
        }
    }
}
