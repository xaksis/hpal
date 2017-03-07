SELECT rest . * , count( * ) AS imcnt
FROM restaurants AS rest
LEFT JOIN images AS i ON rest.id = i.restaurant_id
WHERE rest.id
IN (
	SELECT r.id
	FROM restaurants AS r, restcategories AS rc, categories AS c
	WHERE r.id = rc.restaurant_id
	AND rc.category_id = c.id
	AND c.name LIKE '%chicken lollipop%'
	UNION
	SELECT r.id
	FROM restaurants AS r, dishes AS d, gdishes AS gd
	WHERE d.restaurant_id = r.id
	AND d.gdish_id = gd.id
	AND (
	gd.familyName LIKE '%chicken lollipop%'
	OR gd.name LIKE '%chicken lollipop%'
	)
	UNION
	SELECT r.id
	FROM dishes AS d, restaurants AS r
	WHERE d.restaurant_id = r.id
	AND d.name LIKE '%chicken lollipop%'
	UNION
	SELECT restaurants.id
	FROM restaurants
	WHERE name LIKE '%chicken lollipop%'
)
GROUP BY rest.id
ORDER BY imcnt DESC

SELECT rest . * , count( * ) AS imcnt
FROM restaurants AS rest
LEFT JOIN images AS i ON rest.id = i.restaurant_id
WHERE rest.id
IN
(
select r.id
from restaurants as r left join restcategories as rc on r.id = rc.restaurant_id
left join categories as c on rc.category_id = c.id
left join dishes as d on r.id = d.restaurant_id
left join gdishes as gd on d.gdish_id = gd.id
left join images as i on r.id = i.restaurant_id
where r.name like '%chicken lollipop%'
or c.name like '%chicken lollipop%'
or gd.familyName like '%chicken lollipop%'
or gd.name like '%chicken lollipop%'
or d.name like '%chicken lollipop%'
)
group by rest.id
order by imcnt desc 

SELECT *
					FROM dishes, images
					WHERE images.dish_id = dishes.id
					AND name LIKE '%$s_str%'
					GROUP BY dishes.id
					UNION
					SELECT d . *, i . *
					FROM dishes AS d, images as i, restaurants AS r, restcategories AS rc, categories AS c
					WHERE r.id = rc.restaurant_id
					AND i.dish_id = d.id
					AND rc.category_id = c.id
					AND d.restaurant_id = r.id
					AND catType = 'cuisine'
					AND c.name LIKE '%$s_str%'
					group by d.id
					UNION
					SELECT dishes . * , images . *
					FROM gdishes, dishes, images
					WHERE dishes.gdish_id = gdishes.id
					AND images.dish_id = dishes.id
					AND (
					gdishes.name LIKE '%$s_str%'
					OR gdishes.familyName LIKE '%$s_str%'
					)
					GROUP BY dishes.id
					ORDER BY rating/noOfRatings desc


select d.id, d.restaurant_id, d.name, d.rating, d.noOfRatings, i.imagePath 
from
dishes as d left join images as i on d.id = i.dish_id
left join restaurants as r on d.restaurant_id = r.id
left join restcategories as rc on r.id = rc.restaurant_id
left join categories as c on c.id = rc.category_id
left join dishfeatures as df on df.dish_id = d.id
left join features as f on f.id = df.feature_id
left join gdishes as gd on d.gdish_id = gd.id
where d.name like "%tater%"
or r.name like "%tater%"
or c.name like "%tater%"
or gd.familyName like "%tater%"
or gd.name like "%tater%"
or f.name like "%tater%"
group by d.id
order by rating/noOfRatings desc

select d.id, d.restaurant_id, d.name, d.rating, d.noOfRatings, i.imagePath 
from
dishes as d left join images as i on d.id = i.dish_id
left join dishfeatures as df on df.dish_id = d.id
left join features as f on f.id = df.feature_id
where d.name like "%tater%"
or f.name like "%tater%"
group by d.id
order by d.rating/d.noOfRatings desc


