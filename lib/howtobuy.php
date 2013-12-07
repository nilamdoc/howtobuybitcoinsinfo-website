<?
function get_service_data(){
  return Spyc::YAMLLoad("data/services.yaml");
}

function get_country_data(){
  return Spyc::YAMLLoad("data/countries-codesorted.yaml");
}

function generate_box($service,$currentCountryCode){

  $locationHtml="";
  if (isset($service["location"])){
    foreach($service["location"] as $locationCountryCode){
      if ($locationCountryCode == $currentCountryCode){
        $locationHtml.='<img src="/img/miniflags/'.$locationCountryCode.'.png" onclick="return false;" title="Based in this country." style="float:right;padding:2px;margin-right:0px" />';
      }
    }
  }

  ?>
  <div class="serviceBox <? 
    foreach($service["countries"] as $country):
      echo $country." ";
    endforeach;
  ?>">
      <a href="<?= $service["url"] ?>" target="_blank">
        <h3 class="box-title">
            <img width="16" height="16" src="<?= $service["icon"] ?>"> <?= $service["label"] ?> <?=$locationHtml?>
        </h3>
      </a>
    <div class="box-content">
      <p><?= $service["content"] ?></p>
    </div>
    <div class="left">
      <div class="fb-like" data-href="<?= $service["url"] ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></div>
    </div>
    <div class="right">
      <a class="button" href="<?= $service["url"] ?>" target="_blank">Buy Bitcoins</a>
    </div>
  </div>
  <?
}

function generate_country_boxes($data, $currentCountryCode){
  foreach($data as $service){
    
    //Remove altcoin-only exchanges
    if (
      $service["coins"] && 
      !in_array(strtoupper("btc"), $service["coins"]) && 
      !in_array(strtolower("btc"), $service["coins"])
      ){
      continue;
    }
    
    if (! $service["hidden"] && 
        (
          //Supports this country
          in_array(strtoupper($currentCountryCode), $service["countries"]) ||
          in_array(strtolower($currentCountryCode), $service["countries"])
        ) && 
        isset($service["location"]) && 
        (
          //Is in this country
          in_array(strtoupper($currentCountryCode), $service["location"]) ||
          in_array(strtolower($currentCountryCode), $service["location"])
        )
      ){
      generate_box($service,$currentCountryCode); 
    }
  }
  foreach($data as $service){
    //Remove altcoin-only exchanges
    if (
      $service["coins"] && 
      !in_array(strtoupper("btc"), $service["coins"]) && 
      !in_array(strtolower("btc"), $service["coins"])
      ){
      continue;
    }

    if (! $service["hidden"] && 
        (
          //Supports this country
          in_array(strtoupper($currentCountryCode), $service["countries"]) ||
          in_array(strtolower($currentCountryCode), $service["countries"])
        ) && 
        isset($service["location"]) && 
        (
          //Is not in this country
          !in_array(strtoupper($currentCountryCode), $service["location"]) &&
          !in_array(strtolower($currentCountryCode), $service["location"])
        )
      ){
      generate_box($service,$currentCountryCode); 
    }
  }
}
?>
