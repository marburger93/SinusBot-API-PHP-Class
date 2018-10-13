<?php
/**
 * Class Folder | src/Folder.php
 *
 * A single Folder with his available actions
 *
 * @package      SinusBot
 * @author       Max Schmitt <max@schmitt.mx>
 */

namespace SinusBot;

/**
 * Class Folder
 *
 * Folder represents a single Folder of the SinusBot
 */
class Folder extends RestClient
{
    /**
    * UUID holds the Folder UUID
    * @var array
    */
    public $uuid = null;
    /**
    * Folder stores the initial received folder data
    * @var array
    */
    private $folder = null;
    /**
    * Children stores the folder childrens
    * @var array
    */
    private $children = [];
    /**
    * __construct
    *
    * @param API      $api    SinusBot API
    * @param array    $folder   SiusBot Folder array
    */
    public function __construct($api, $folder)
    {
        parent::__construct($api);
        $this->uuid = $folder['uuid'];
        $this->folder = $folder;
    }
    
    /**
    * getTitle returns the title
    *
    * @return string foldername
    * @api
    */
    public function getTitle()
    {
        return $this->folder['title'];
    }

    /**
    * getType returns the file type
    *
    * @return string type: url, folder
    * @api
    */
    public function getType()
    {
        return array_key_exists('type', $this->folder)?$this->folder['type']:'';
    }

    /**
    * getUUID returns the uuid
    *
    * @return string folder UUID
    * @api
    */
    public function getUUID()
    {
        return $this->uuid;
    }

    /**
    * getUUID returns the uuid
    *
    * @return string folder UUID
    * @api
    */
    public function getParent()
    {
        return $this->folder["parent"];
    }

    /**
    * shouldContainsChildren checks if the given file should be
    *
    * @return string folder UUID
    * @api
    */
    public function addChildrenIfOK($file)
    {
        if ($this->getUUID()=== $file->getParent()) {
            array_push($this->children, $file);
            return true;
        }
        foreach ($this->children as $children) {
            if ($children->getType() === "folder") {
                if ($children->addChildrenIfOK($file)) {
                    return true;
                }
            }
        }
    }

    /**
    * getChildren returns the children of the folder
    *
    * @return (\File|\Folder)[]
    * @api
    */
    public function getChildren()
    {
        return $this->children;
    }

    /**
    * delete
    *
    * @return array status
    * @api
    */
    public function delete()
    {
        return $this->request('/bot/folders/'.$this->uuid, 'DELETE');
    }
}
