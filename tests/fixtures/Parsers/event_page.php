<?php

$return = [];

$return['races'][] = new \Sportic\Timing\RaceTecClient\Models\Race(
    ['name' => 'Olimpic', 'href' => 'Results.aspx?CId=16648&RId=2091&EId=1']
);
$return['races'][] = new \Sportic\Timing\RaceTecClient\Models\Race(
    ['name' => 'Stafeta Olimpic', 'href' => 'Results.aspx?CId=16648&RId=2091&EId=3']
);
$return['races'][] = new \Sportic\Timing\RaceTecClient\Models\Race(
    ['name' => 'Sprint', 'href' => 'Results.aspx?CId=16648&RId=2091&EId=2']
);
$return['races'][] = new \Sportic\Timing\RaceTecClient\Models\Race(
    ['name' => 'Stafeta Sprint', 'href' => 'Results.aspx?CId=16648&RId=2091&EId=4']
);
$return['races'][] = new \Sportic\Timing\RaceTecClient\Models\Race(
    ['name' => 'Supersprint', 'href' => 'Results.aspx?CId=16648&RId=2091&EId=5']
);

return $return;
