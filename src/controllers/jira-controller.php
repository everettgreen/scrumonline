<?php
  
/*
 * Jira controller class to handle all Jira operations
 */
class JiraController extends ControllerBase
{
    public function getIssues()
    {
        //$parameters = array_merge((array) $jiraConfiguration, $_POST);
        $parameters = $_POST;

        $jiraUrl = $parameters['base_url'] . '/rest/api/latest/search?jql=';
        $params = [];
        if ($parameters['project']) {
            $params[] = 'project=' . $parameters['project'];
        }
        if ($parameters['jql']) {
            $params[] = $parameters['jql'];
        }
        $jiraUrl .= implode(' and ', $params);

        if (substr_count(strtolower($parameters['jql']), "order by") == 0 && substr_count(strtolower($parameters['jql']), "order%20by") == 0) {
            //$jiraUrl .= ' order by priority';
        }

        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $jiraUrl, [
            'auth' => [$parameters['username'], $parameters['password']]
        ]);
        $response = json_decode($res->getBody()->getContents(), true);
        return $response;
    }
}

return new JiraController($entityManager);
