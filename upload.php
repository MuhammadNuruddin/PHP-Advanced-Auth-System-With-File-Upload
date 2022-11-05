<?php 
require_once 'core/init.php';
ini_set('display_errors',0);
if(Input::exists()) {
    if(Token::check(Input::get('token'))){
        $validate = new Validate();
        $validation = $validate->check($_POST,array(
            'brand' => array(
                'field_name' => 'Brand'
            ),
            'price' => array(
                'required' => true,
                'min' => 1,
                'number' => true,
                'field_name' => 'Price'
            ),
            'description' => array(
                'required' => true,
                'field_name' => 'Description'
            ),
            'name' => array(
                'required' => true,
                'field_name' => 'Name'
            ),
            'image' => array(
                // 'required' => true,
                // 'file' => true,
                'field_name' => 'Image'
            )
    
        ));

        // $errors = array();
        $img_errors = array();
        if(Input::check('image')) {
            
            $target_dir = "uploads/";
            // $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $is_passed = 1;
            $imageFileType = strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));


              // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                // $validation->addError('image', 'Sorry, your file is too large.');
                array_push($img_errors, array('Sorry, your file is too large'));
                $is_passed = 0;
            }
            
            // Allow certain file formats
            $allowed = array('gif', 'png', 'jpg', 'jpeg');
            if(!in_array($imageFileType, $allowed)) {
                // $validation->addError('image', 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
                array_push($img_errors, array('Sorry, only JPG, JPEG, PNG & GIF files are allowed'));
                $is_passed = 0;
            }
            if($is_passed) {
                $img_name = uniqid(true);
                $image = $img_name.'.'.$imageFileType;
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir.$image)) {
                    // $validation->addError('image', 'Sorry, there was an error uploading your file.');
                    array_push($img_errors, array('Sorry, there was an error uploading your file'));
                }else {
                    $is_passed = 1;
                }
            }

            
        }else {
            array_push($img_errors, array('Image is required'));
        }
        if ($validation->passed() && $is_passed) {
            try {


                $db = DB::connect();
                $db->insert('product', [
                    'name' => Input::get('name'),
                    'brand' => Input::get('brand'),
                    'price' => Input::get('price'),
                    'description' => Input::get('description'),
                    'image' => $image,
                ]);
    
                Session::flash('home', 'Product Uploaded Successfully!');
                Redirect::to('index.php');
    
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }else {
            $errors = $validation->errors();
            // array_push($errors, $img_errors);
            // var_dump($img_errors);
        }


    }
}

?>


<head>
	<style>
		.error {
			border:1px solid red;
		}
		</style>
</head>
<form action="" method="POST" enctype="multipart/form-data">
<div class="field">
	<label for="brand">Brand</label>
	<input type="text" name="brand" 
    class="<?php if(isset($errors) && check_array('brand',$errors)) echo 'error' ?>" 
    id="brand" value="<?= escape(Input::get('brand')) ;?>" autocomplete="off">
	<br>
	<small style='color:red'><?php if(isset($errors)) check_error('brand',$errors); ?></small>
	
</div>

<div class="field">
	<label for="name">Name</label>
	<input type="text" name="name" 
    class="<?php if(isset($errors) && check_array('name',$errors)) echo 'error' ?>" 
    id="name" value="<?= escape(Input::get('name')) ;?>" autocomplete="off">
	<br>
	<small style='color:red'><?php if(isset($errors)) check_error('name',$errors); ?></small>
	
</div>

<div class="field">
	<label for="price">Price</label>
	<input type="number" name="price" id="price" min="1"
    class="<?php if(isset($errors) && check_array('price',$errors)) echo 'error' ?>" 
    value="<?= escape(Input::get('price')) ;?>">
	<br>
	<small style='color:red'><?php if(isset($errors)) check_error('price',$errors); ?></small>
</div>	

<div class="field">
	<label for="description">Description</label>
	<textarea name="description" id="description" 
    class="<?php if(isset($errors) &&check_array('description',$errors)) echo 'error' ?>">
    <?= escape(Input::get('description')) ;?>
    </textarea>
	<br>
	<small style='color:red'><?php if(isset($errors)) check_error('description',$errors); ?></small>	
</div>	

<div class="field">
	<label for="image">Image</label>
	<input type="file" name="image" id="image"
	class="<?php if((isset($errors) && check_array('image',$errors)) || isset($img_errors)) echo 'error' ?>"
	 autocomplete="off">
<br>
    <?php if(isset($img_errors)): ?>
        <?php foreach($img_errors as $error => $err): ?>
            <?php foreach($err as $e): ?>
                <small style="color:red"><?= $e ?></small>
                <?php endforeach ?>
            
        <?php endforeach ?>
   <?php endif; ?>
</div>


<input type="hidden" name="token" value="<?= Token::generate() ;?>">

<input type="submit" value="Upload">
</form>