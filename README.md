# === Count Slaps ===
Version:			1.2
Contributors: (funcyChaos)
Tags: 				count, slaps
License: 			GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

## == Description ==

A plugin to count slaps for FOTA fights at Chilis

Securely and rapidly update a small table to count slaps in the wordpress database using a transaction every 800ms

<img src="./slap-fight.png" alt="latest counter">

### Required ID"s:
(where x is the team number)

```
#xml_count_x 	= ID to show/adjust teamx"s count
(Deprecated right now because of Visual Composer;
	ID plus two children deep ie [0][0]
)

#teamx_bonus 	= ID to show teamx"s bonus

#slap_btn_x 	= ID that will add slaps to teamx when clicked
```

### Classes:

```
.bonus-styles = default invisible style
```

#### See dev_render in count_slaps.php for an example