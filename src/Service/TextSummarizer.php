<?php


namespace App\Service;

use App\Entity\BlogTopic;
use PhpScience\TextRank\TextRankFacade;
use PhpScience\TextRank\Tool\StopWords\English;

class TextSummarizer
{
    public function summarize(BlogTopic $topic)
    {
        $content = strip_tags($topic->getText());
        $content = str_replace("\n", "", $content);
        $content = str_replace("\r", "", $content);
        $content = preg_replace("/&nbsp;/", '', $content);
        $api          = new TextRankFacade();
        $summaryArray = $api->summarizeTextBasic($content);

        return implode("", $summaryArray);
    }
}
