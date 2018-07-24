<?php

$klein->respond('/docs', function ($req, $res, $service) {
    $service->pageTitle = 'Documentation | EMAC Template';
    $service->isMiniHeader = true;
    $service->isHiddenSidebar = true;
    $service->docs_markdown = file_get_contents("README.md");
    $service->render("pages/documentation.php");
});