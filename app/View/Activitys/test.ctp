<!doctype html>
 
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>jQuery UI Accordion - Default functionality</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
  <script>
    $(function() {
      var one = <?php echo $index[0];?>;
      var two = <?php echo $index[1];?>;
      var the = <?php echo $index[2];?>;
      var settings = {collapsible: true,heightStyle: "content",active: false};
      
      $( ".accordion1" ).accordion(settings);
      $( ".accordion2" ).accordion(settings);
      $( ".accordion3" ).accordion(settings);
      
      $( ".accordion1" ).accordion("option","active",one);
      $( ".accordion2" ).accordion("option","active",two);
      $( ".accordion3" ).accordion("option","active",the);
      
    });
  </script>
</head>
<body>
<div class="accordion1"><!-- 1 -->
  <h3>Section 1</h3>
  <div>
    <p>
    Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
    ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
    amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
    odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.
    </p>
  </div>
  <h3>Section 2</h3>
  <div>
    <div class="accordion2"><!-- 2 -->
        <h3>sub 1</h3>
        <p>sub one content</p>
        <h3>sub 2</h3>
        <div class="accordion3"><!-- 3 -->
            <h3>nest 1</h3>
            <p id="n1">nest content 1</p>
            <h3>nest 2</h3>
            <p id="n2">nest content 2</p>
            <h3>nest 3</h3>
            <p id="n3">nest content 3</p>
        </div>
        <h3>sub 4</h3>
        <p>sub 4 content</p>
    </div>
  </div>
  <h3>Section 3</h3>
  <div>
    <p>
    Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
    Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
    ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
    lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
    </p>
    <ul>
      <li>List item one</li>
      <li>List item two</li>
      <li>List item three</li>
    </ul>
  </div>
  <h3>Section 4</h3>
  <div>
    <p>
    Cras dictum. Pellentesque habitant morbi tristique senectus et netus
    et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
    faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
    mauris vel est.
    </p>
    <p>
    Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
    Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
    inceptos himenaeos.
    </p>
  </div>
</div>
 
</body>
</html>