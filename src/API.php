<?php
/**
 * Discourse API client for PHP
 * @link         https://github.com/sgvsv/php-discourse
 **/

namespace sgvsv\Discourse;


class API
{
    private $forumEndpoint;
    private $forumAPIKey;
    private $forumAPIUser = 'system';
    private $forumShowEmails = true;

    public function __construct(string $URL, string $APIKey, string $APIUser = 'system', bool $showEmails = true)
    {
        $this->forumEndpoint = $URL;
        $this->forumAPIKey = $APIKey;
        $this->forumAPIKeyAPIUser = $APIUser;
        $this->forumShowEmails = $showEmails;
    }

    /**
     * latestTopics
     * @param string $category slug of category
     * @return mixed HTTP return code and API return object
     */
    public function latestTopics($category = null)
    {
        $reqString = (!empty($category) ? ('/c/' . $category . '/l') : '') . '/latest.json';
        return $this->_getRequest($reqString);
    }
    /** @noinspection MoreThanThreeArgumentsInspection */
    /**
     * @param string $reqString
     * @param array $paramArray
     * @return \stdClass
     *
     **/
    private function _getRequest(string $reqString, array $paramArray = []): \stdClass
    {
        $paramArray = array_merge($paramArray, $this->defaultParams());
        $ch = curl_init();
        $url = $this->forumEndpoint . $reqString . '?' . http_build_query($paramArray);
        curl_setopt($ch, CURLOPT_URL, $url);
        echo $url;
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $HTTPMETHOD);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $body = curl_exec($ch);
        $rc = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $resObj = new \stdClass();
        $resObj->http_code = $rc;
        // Only return valid json
        $json = json_decode($body);
        $resObj->apiresult = $body;
        if (json_last_error() === JSON_ERROR_NONE) {
            $resObj->apiresult = $json;
        }

        return $resObj;
    }

    private function defaultParams()
    {
        return ['api_key' => $this->forumAPIKey,
            'api_username' => $this->forumAPIUser,
            'show_emails' => $this->forumShowEmails,
        ];
    }
}