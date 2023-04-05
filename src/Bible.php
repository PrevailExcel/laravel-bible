<?php

namespace PrevailExcel\Bible;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Config;

/*
 * This file is part of the Laravel Bible package.
 *
 * (c) Prevail Ejimadu <prevailexcellent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Bible
{

    /**
     * Issue API Key from your Bible Dashboard
     * @var string
     */
    protected $apiKey;

    /**
     * Instance of Client
     * @var Client
     */
    protected $client;

    /**
     *  Response from requests made to Bible
     * @var mixed
     */
    protected $response;

    /**
     * API.Bible API base Url
     * @var string
     */
    protected $baseUrl;

    /**
     * API.Bible API base Url
     * @var string
     */
    protected $version;

    public function __construct()
    {
        $this->setKey();
        $this->setBaseUrl();
        $this->setRequestOptions();
    }

    /**
     * Get Base Url from Bible config file
     */
    private function setBaseUrl()
    {
        $this->baseUrl = Config::get('bible.url');
    }

    /**
     * Get api key from Bible config file
     */
    private function setKey()
    {
        $this->apiKey = Config::get('bible.apiKey');
    }

    /**
     * Get default version from Bible config file
     */
    private function setVersion(string $version_name = null)
    {
        if ($version_name)
            $version = array_search($version_name, array_column(Config::get('bible.versions'), 'name'));
        else
            $version = array_search(Config::get('bible.default'), array_column(Config::get('bible.versions'), 'name'));

        $this->version = Config::get('bible.versions');
        $this->version = $this->version[$version]['id'];

        return $this->version;
    }

    /**
     * Set options for making the Client request
     */
    private function setRequestOptions()
    {
        $headers = [
            'api-key' => $this->apiKey,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json'
        ];
        $this->client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'headers' => $headers
            ]
        );
    }

    /**
     * @param string $relativeUrl
     * @param string $method
     * @param array $body
     * @return Bible
     * @throws IsNullException
     */
    private function setHttpResponse($relativeUrl, $method, array $query = null)
    {
        if (is_null($method)) {
            throw new IsNullException("Empty method not allowed");
        }

        $this->response = $this->client->{strtolower($method)}(
            $this->baseUrl . $relativeUrl,
            [RequestOptions::QUERY => $query
            ]        
       );
        return $this;
    }

    /**
     * Get the whole response from a get operation
     * @return array
     */
    private function getResponse()
    {
        return json_decode($this->response->getBody(), true);
    }

    /**
     * Get all available bibles
     * 
     * @return array
     * @throws isNullException
     */
    public function bibles(): array
    {
        $this->setRequestOptions();
        return $this->setHttpResponse("/bibles", 'GET', [])->getResponse();
    }

    /**
     * Get a particular bible
     * 
     * @param string $version
     * @return array
     * 
     */
    public function bible($version = null): array
    {
        if (!$version)
            $bible_id = $this->setVersion(request()->version) ?? $this->setVersion();
        else
            $bible_id = $this->setVersion($version);

        return $this->setHttpResponse('/bibles/' . $bible_id, 'GET')->getResponse();
    }

    /**
     * @param string $version
     * @return array
     * 
     */
    public function books($version = null): array
    {
        if (!$version)
            $bible_id = $this->setVersion(request()->version) ?? $this->setVersion();
        else
            $bible_id = $this->setVersion($version);

        return $this->setHttpResponse('/bibles/' . $bible_id . '/books', 'GET')->getResponse();
    }

    /**
     * Get a book of the bible in any version or language.
     * 
     * @param string $version
     * @param string $book
     * @return array
     * 
     */
    public function book($book = null, $version = null): array
    {
        if (!$version)
            $bible_id = $this->setVersion(request()->version) ?? $this->setVersion();
        else
            $bible_id = $this->setVersion($version);

        if ($book == null) {
            $book = request()->book ?? 'GEN';
        }
        return $this->setHttpResponse('/bibles/' . $bible_id . '/books/' . $book, 'GET')->getResponse();
    }

    /**
     * Get the chapters of any book of the bible in any version or language.
     * 
     * @param string $version
     * @param string $book
     * @return array
     * 
     */
    public function chapters($book = null, $version = null): array
    {
        if (!$version)
            $bible_id = $this->setVersion(request()->version) ?? $this->setVersion();
        else
            $bible_id = $this->setVersion($version);

        if ($book == null) {
            $book = request()->book ?? 'GEN';
        }

        return $this->setHttpResponse('/bibles/' . $bible_id . '/books/' . $book . '/chapters', 'GET')->getResponse();
    }

    /**
     * Get a chapter of the bible from any book in any version or language.
     * 
     * @param string $version
     * @param string $book
     * @param integer $chapter
     * 
     * @return array
     * 
     */
    public function chapter($chapter = null, $book = null, $version = null): array
    {
        if (!$version)
            $bible_id = $this->setVersion(request()->version) ?? $this->setVersion();
        else
            $bible_id = $this->setVersion($version);

        if ($chapter == null)
            $chapter = request()->chapter ?? 1;

        if ($book == null)
            $book = request()->book ?? 'GEN';

        $chapter = $book . '.' . $chapter;

        return $this->setHttpResponse('/bibles/' . $bible_id . '/chapters/' . $chapter, 'GET')->getResponse();
    }

    /**
     * Get a verse of the bible from any chapter in any book of any version or language.
     * 
     * @param string|null $bible_id
     * @param string|null  $book
     * @param integer|null  $chapter
     * @param integer|null  $verse
     * 
     * @return array
     * 
     */
    public function verse($verse = null, $chapter = null, $book = null, $version = null): array
    {
        if (!$version)
            $bible_id = $this->setVersion(request()->version) ?? $this->setVersion();
        else
            $bible_id = $this->setVersion($version);

        if ($chapter == null)
            $chapter = request()->chapter ?? 1;

        if ($verse == null)
            $verse = request()->verse ?? 1;

        if ($book == null)
            $book = request()->book ?? 'GEN';

        $verse = $book . '.' . $chapter . '.' . $verse;

        return $this->setHttpResponse('/bibles/' . $bible_id . '/verses/' . $verse, 'GET')->getResponse();
    }

    /**
     * Get the verses from a chapter of the bible from any book in any version or language.
     * 
     * @param string $version
     * @param string $book
     * @param integer $chapter
     * 
     * @return array
     * 
     */
    public function verses($chapter = null, $book = null, $version = null): array
    {
        if (!$version)
            $bible_id = $this->setVersion(request()->version) ?? $this->setVersion();
        else
            $bible_id = $this->setVersion($version);

        if ($chapter == null)
            $chapter = request()->chapter ?? 1;

        if ($book == null)
            $book = request()->book ?? 'GEN';

        $chapter = $book . '.' . $chapter;

        return $this->setHttpResponse('/bibles/' . $bible_id . '/chapters/' . $chapter . '/verses', 'GET')->getResponse();
    }

    /**
     * Get a passage from the bible.
     * 
     * @param string $version
     * @param string $passages
     * @return array
     * 
     */
    public function passages($passages = null, $version = null, $type = 'json'): array
    {
        if (!$version)
            $bible_id = $this->setVersion(request()->version) ?? $this->setVersion();
        else
            $bible_id = $this->setVersion($version);

        if ($passages == null) {
            $passages = request()->passages ?? 'GEN.1';
        }
        return $this->setHttpResponse('/bibles/' . $bible_id . '/passages/' . $passages, 'GET', ['content-type' => $type])->getResponse();
    }

    /**
     * 
     * Searches will match all verses with the list of keywords provided in the query string.
     * Order of the keywords does not matter. However all keywords must be
     * present in a verse for it to be considered a match.
     * The total number of results returned from a search can be limited by populating the limit
     * attribute in the query string with a non-negative integer value. If no
     * limit value is provide a default of 10 is used.
     *  'query' =      Search keywords or passage reference. Supported wildcards are * and ?.
     *                 The * wildcard matches any character sequence (e.g. searching for "wo*d" finds text such as "word", "world", and "worshipped").
     *                 The ? wildcard matches any matches any single character (e.g. searching for "l?ve" finds text such as "live" and "love").
     *  'limit' =      Integer limit for how many matching results to return. Default is 10.
     *  'offset' =     Offset for search results. Used to paginate results
     *  'sort' =       Sort order of results. Supported values are relevance (default), canonical and reverse-canonical
     *  'range' =      One or more, comma seperated, passage ids (book, chapter, verse) which the search will be limited to. 
     *                  (i.e. gen.1,gen.5 or gen-num or gen.1.1-gen.3.5)
     *  'fuzziness' =  Sets the fuzziness of a search to account for misspellings. Values can be 0, 1, 2, or AUTO. 
     *                  Defaults to AUTO which varies depending on the Available values
                
     * @param array $data
     * @param string $version
     * @return array
     * 
     */
    public function search(array $data = null, $version = null): array
    {
        if (!$data) {
            $data = [
                'query' => request()->term,
                'limit' => request()->limit,
                'offset' => request()->offset,
                'sort' => request()->sort,
                'range' => request()->range,
                'fuzziness' => request()->fuzziness,
            ];
        }

        if (!$version)
            $bible_id = $this->setVersion(request()->version) ?? $this->setVersion();
        else
            $bible_id = $this->setVersion($version);

        return $this->setHttpResponse('/bibles/' . $bible_id . '/search', 'GET', $data)->getResponse();
    }
}
