<?php

namespace codebase\Projects;

class ProjectManager {

    private $PROJECTS = array();

    public function selectProjectByName($title){
        if($title == null) { return; }
        
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT * FROM projects WHERE `title` = :TITLE ORDER BY id DESC');
        $STMT->bindParam(':TITLE', $title, \PDO::PARAM_STR);
        $STMT->execute();
        $STMT->setFetchMode(\PDO::FETCH_CLASS, __NAMESPACE__ . '\\Project');
        while ($result = $STMT->fetch(\PDO::FETCH_CLASS)) {
            array_push($this->PROJECTS, $result);
        }
    }
    
    public function selectProjects($limit = false){
        if ($limit != false) {
            $limit = 'LIMIT '.$limit;
        } else {
            $limit = '';
        }
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT * FROM projects ORDER BY id DESC ' . $limit);
        $STMT->execute();
        $STMT->setFetchMode(\PDO::FETCH_CLASS, __NAMESPACE__ . '\\Project');
        while ($result = $STMT->fetch(\PDO::FETCH_CLASS)) {
            array_push($this->PROJECTS, $result);
        }
    }

    public function designProjects() {
        $detailed_projects = '';
        $projects_HTML = '';
        foreach ($this->getProjects() as $project) {
            $detailed_projects .= '
                                        <div id="slidingDiv' . $project->id . '" class="toggleDiv row-fluid single-project">
                                            <div class="span6">
                                                <img src="' . $project->image_md . '" alt="' . $project->title . ' Image" />
                                            </div>
                                            <div class="span6">
                                                <div class="project-description">
                                                    <div class="project-title clearfix">
                                                        <h3>' . $project->title . '</h3>
                                                        <span class="show_hide close">
                                                            <i class="icon-cancel"></i>
                                                        </span>
                                                    </div>
                                                    <div class="project-info">
                                                        '.($project->client != null ? '<div><span>Client</span>' . $project->client . '</div>' : '').'
                                                        <div>
                                                            <span>Date</span>' . date('F Y', strtotime($project->date)) . '</div>
                                                        <div>
                                                            <span>Skills</span>' . $project->skills . '</div>
                                                        <div class="center">
                                                        ' . ($project->link_download != null ? '<a href="' . $project->link_download . '" class="button btn-yellow">Download</a>' : '')
                    . ($project->link_livepreview != null ? '<a href="' . $project->link_livepreview . '" class="button btn-yellow">Live Preview</a>' : '')
                    . ($project->link_source != null ? '<a href="' . $project->link_source . '" class="button btn-yellow">Source</a>' : '') . '
                                                        </div>
                                                    </div>
                                                    <p>' . $project->description . '</p>
                                                    <div class="center">'. ($project->extended_description != null ? ' <a href="/project/?&title=' . $project->title . '" class="link-blue">Click Here</a> for more information about this project.' : '').'</div>
                                                </div>
                                            </div>
                                        </div>';
            $projects_HTML .= '
                                    <li class="span4 mix ' . $project->category . '">
                                        <div class="thumbnail">
                                            <img src="' . $project->image_sm . '" alt="' . $project->title . ' Image">
                                            <a href="#single-project" class="more show_hide" rel="#slidingDiv' . $project->id . '">
                                                <i class="icon-plus"></i>
                                            </a>
                                            <h3>' . $project->title . '</h3>
                                            <p>' . $project->short_description . '</p>
                                            <div class="mask"></div>
                                        </div>
                                    </li>';
        }
        return $detailed_projects.'<ul id="portfolio-grid" class="thumbnails row">' . $projects_HTML . '</ul>';
    }

    public function getProjects() {
        return $this->PROJECTS;
    }

}

class Project {

    public $id;
    public $title;
    public $short_description;
    public $description;
    public $extended_description;
    public $category;
    public $client;
    public $date;
    public $skills;
    public $image_sm;
    public $image_md;
    public $image_lg;
    public $link_download;
    public $link_livepreview;
    public $link_source;

}
