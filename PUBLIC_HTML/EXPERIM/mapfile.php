class MapFile {
     public $filename;
     private $innermap;

     function __construct($filename) {
          $this->$filename = $filename;
          $this->load();
     }

     public function load() {
          $text = file_get_contents($this->$filename);
          $this->$innermap = array();
          foreach (explode("***", $text) as $section) {
               $pieces = explode(";", $section);
               //  I think shift gets rid of an array element, but I want to delete the first one
               shift ($pieces);
               $this->$innermap[$pieces[0]] = $pieces;
               // Each piece is a an array consisting of  [text of question, list of answers]
          }
     }

     public function save() {
          $text = "";
          foreach (keys($this->$innermap) as $key) {
                $pieces = $this->$innermap[$key];
                $section = $key . ";" . join(";",$pieces);
                if ($text != "")
                     $text = "\n***\n" . $section;
                else
                     $text = $section;
          }
          file_put_contents($this->$filename, $text);
     }
  
     public function get($title) {
          if (containskey($this->$innermap, $title)) 
               return $this->$innermap[$title];
          else
               return null;   // is there such a thing?
     }

     public function put($title, $newvalue) {
         $this->$innermap[$title] = $newvalue;
     }

     public function getQuestion($pieces) {
          return $pieces[0];
     }

     public function getAnswer($pieces) {
          return $pieces[1];
     }

     public function putQuestion($pieces, $newquestion) {
          $pieces[0] = $newquestion;
     }

     public function putAnswer($pieces, $newanswer) {
          $pieces[1] = $newanswer;
     }
}