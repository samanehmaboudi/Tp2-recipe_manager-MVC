{{ include('layouts/header.php', {title:'Recipes'}) }}

<h1>Recipes</h1>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Ingredients</th>
            <th>Instructions</th>
            <th>Category</th>
            <th>View</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        {% for recipe in recipes %}
        <tr>
            <td>{{ recipe.title }}</td>
            <td>{{ recipe.ingredients }}</td>
            <td>{{ recipe.instructions }}</td>
            <td>{{ recipe.category_name }}</td>
            <td><a href="{{ base }}/recipe/{{ recipe.id }}" class="btn">View</a></td>
            <td><a href="{{ base }}/recipe/{{ recipe.id }}/edit" class="btn">Edit</a></td>
            <td>
                <form action="{{ base }}/recipe/{{ recipe.id }}/delete" method="post">
                    <input type="hidden" name="id" value="{{ recipe.id }}">
                    <input type="submit" class="btn red" value="Delete">
                </form>
            </td>
        </tr>
        {% endfor %}
    </tbody>
</table>

<br><br>
<a href="{{ base }}/recipe/create" class="btn">New Recipe</a>

{{ include('layouts/footer.php') }}
