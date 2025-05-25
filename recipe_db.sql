-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 12:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recipe_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmark`
--

CREATE TABLE `bookmark` (
  `bookmarkId` int(255) NOT NULL,
  `recipeId` int(255) NOT NULL,
  `userId` int(255) NOT NULL,
  `ingredientId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartId` int(255) NOT NULL,
  `userId` int(255) NOT NULL,
  `recipeId` int(255) NOT NULL,
  `quantity` int(3) NOT NULL,
  `ingredientId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentId` int(255) NOT NULL,
  `userId` int(255) NOT NULL,
  `recipeId` int(255) NOT NULL,
  `comment` text NOT NULL,
  `createdOn` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredientId` int(255) NOT NULL,
  `ingredientName` varchar(50) NOT NULL,
  `quantity` int(3) NOT NULL,
  `price` varchar(10) NOT NULL,
  `image` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredientId`, `ingredientName`, `quantity`, `price`, `image`) VALUES
(3, 'Milk', 10, '2', 'milk.jpg'),
(4, 'Eggs', 10, '3', 'eggs.webp'),
(5, 'Toast', 10, '2', 'bread.avif'),
(6, 'Buns', 10, '2', 'buns bread.jpg'),
(7, 'Cheese', 10, '3', 'cheese.webp'),
(8, 'Apple', 10, '2', 'apple.jpg'),
(9, 'Chicken', 10, '5', 'pilic-roaster.jpg'),
(10, 'Chicken Breasts', 10, '3', 'chicken breast.jpg'),
(11, 'Cucumber', 10, '1', 'cucumber.jpg'),
(12, 'Carrots', 10, '2', 'carrots.jpg'),
(13, 'Wrap Bread ', 10, '2', 'wrap-bread.jpg'),
(14, 'Salt', 10, '2', 'salt.webp'),
(15, 'Tomatoes', 10, '2', 'Tomatoes.webp'),
(16, 'Beef Mince', 10, '5', 'beef mince.jpg'),
(17, 'Beef Patties', 10, '4', 'burger-patties.jpg'),
(18, 'Butter', 10, '4', 'butter.avif');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `messageId` int(255) NOT NULL,
  `userId` int(255) NOT NULL,
  `userName` varchar(20) NOT NULL,
  `userEmail` varchar(30) NOT NULL,
  `messageContent` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` int(255) NOT NULL,
  `userId` int(255) NOT NULL,
  `userName` varchar(20) NOT NULL,
  `userEmail` varchar(30) NOT NULL,
  `number` varchar(15) NOT NULL,
  `orderMethod` varchar(20) NOT NULL,
  `userAddress` varchar(300) NOT NULL,
  `totalRecipes` varchar(300) NOT NULL,
  `totalPrice` varchar(10) NOT NULL,
  `placedOn` date NOT NULL,
  `paymentStatus` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `recipeId` int(255) NOT NULL,
  `image` varchar(300) NOT NULL,
  `recipeName` varchar(50) NOT NULL,
  `recipeDescription` varchar(700) NOT NULL,
  `recipeMethod` varchar(1000) NOT NULL,
  `recipeIngredient` varchar(500) NOT NULL,
  `prepTime` int(10) NOT NULL,
  `cookTime` int(10) NOT NULL,
  `calories` int(10) NOT NULL,
  `recipeNutrients` varchar(500) NOT NULL,
  `price` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`recipeId`, `image`, `recipeName`, `recipeDescription`, `recipeMethod`, `recipeIngredient`, `prepTime`, `cookTime`, `calories`, `recipeNutrients`, `price`) VALUES
(21, 'Gochujang-Noodles-4.jpg', 'Saucy Gochujang Noodles', 'What Is Gochujang?\r\nIf you’re not familiar, gochujang — a staple ingredient in Korean cooking with an absolutely delicious lingering heat and strong umami flavour — mixed with some other ingredients like soy sauce, vinegar, and a sweetener. Gochujang itself is a spicy-sweet-savoury paste that is made from fermented soybeans, red chile pepper flakes, sticky rice, and salt and it is commonly used to flavour meat dishes, soups and stews, and sauces. You can read a little more about how it’s made and used from Christina Chaey over at Bon Appetit.', 'Whisk the sauce ingredients (except the extra broth) in a small bowl or shake together in a jar. It should form a thick sauce.\r\n\r\nCook the chicken in a large skillet over medium high heat. Season generously with salt and pepper. \r\nBoil the noodles for just a few minutes to soften. Drain and set aside.\r\n\r\nWhen the chicken is done, add spinach, cooked noodles, and sauce to the pan, keeping it over medium high heat. Toss to combine; heat until the spinach is wilted. Add extra water or broth to thin the sauce, a little at a time, to get the sauciness that you like (I usually add about 1 1/2 cups total).\r\n\r\nServe topped with fresh herbs, scallions, chili oil, sesame seeds, and whatever else you like. \r\n', '3 tablespoon soy sauce\r\n2–3 tablespoons gochujang sauce\r\n2 tablespoon tomato paste\r\n2 tablespoon peanut butter\r\n2 tablespoon water\r\n1–2 tablespoons brown sugar\r\n1 tablespoon sesame oil\r\n1 clove minced garlic\r\n1–2 cups broth or water for thinning the sauce\r\n448 grams ground chicken\r\n 1/2 teaspoon salt\r\nfreshly ground black pepper\r\n2 packets ramen or stir fry noodles\r\n30–60 grams fresh spinach\r\n4 grams chives, scallions, cilantro, basil, or whatever herbs you like for topping\r\nsalt to taste\r\n1 tab', 10, 20, 435, 'Total Fat 17.4g\r\nCholesterol 96.3mg\r\nSodium 1012.2mg\r\nTotal Carbohydrate 41.9g\r\nDietary Fiber 2.5g\r\nSugars 7.3g\r\nProtein 28.6g\r\nVitamin A 54.3µg\r\nVitamin C 9.5mg\r\nIron 3.2mg\r\nPotassium 1236.4mg\r\nPhosphorus 333mg', '20'),
(22, 'Greek-Baked-Orzo-1.jpg', 'Greek Baked Orzo', 'Healthy? Cozy? This one can check both boxes for us.\r\nThe thing about orzo is that it’s one of those types of pasta that can kind of get lost in obscurity, but whenever I make it I find myself thinking: I NEED TO MAKE THAT MORE.', 'Preheat the oven to 400 degrees.\r\n\r\nIn a large oven-safe skillet, heat the oil over medium heat. Add the onion. Saute for 5 minutes or until soft.\r\n\r\nAdd the garlic, red pepper, kale, oregano, red pepper flakes, and salt. Saute for 5 minutes or until the kale is wilted.\r\n\r\nAdd the tomato paste. Saute for 1-2 minutes. \r\n\r\nAdd the orzo, canned tomatoes, chicken or chickpeas, and broth. Bring to a simmer.\r\n\r\nBake for 10-15 minutes until the orzo is soft.\r\n\r\nFinish by stirring in butter, crumbling feta over the top, and dusting with some fresh dill, lemon juice, and freshly ground black pepper. YEAH BABY.', '2 tablespoon olive oil\r\n1/2 onion, diced\r\n2 garlic cloves, minced\r\n1 red bell pepper, diced\r\n32 grams kale, chopped\r\n2 teaspoon dried oregano\r\na tiny pinch of red pepper flakes\r\n1 teaspoon kosher salt\r\n3 tablespoon tomato paste\r\n91 grams uncooked orzo\r\none 14-ounce can diced fire-roasted tomatoes\r\n140–280 grams cooked chicken or chickpeas\r\n593 ml vegetable or chicken broth\r\n1–2 tablespoons butter (optional)\r\n75 grams feta for topping (optional)\r\n3 tablespoon dill for topping (optional)\r\nlemon sq', 20, 30, 276, 'Total Fat 7.7g\r\nCholesterol 17.5mg\r\nSodium 500.2mg\r\nTotal Carbohydrate 38.5g\r\nDietary Fibre 3.2g\r\nSugars 5.9g\r\nProtein 14.4g\r\nVitamin A 76.4µg\r\nVitamin C 40.1mg\r\nIron 3.2mg\r\nPotassium 542.1mg\r\nPhosphorus 182.3mg', '15'),
(23, 'Scallion-Pancake-with-Egg.jpg', 'Scallion Pancake with Eggs', 'A frozen scallion pancake, loaded with eggs, spinach, avocado and chili crisp! A five minute meal perfect for breakfast, lunch, or dinner!', 'Fry your scallion pancake in the oil until golden on both sides. Remove from the pan and set aside. (I use a non-stick pan for this.)\r\n\r\nIn the same pan, add a handful of spinach to wilt it down. When it’s softened, scoot it towards the centre of the pan so the scallion pancake will catch all of it when you put it back down.\r\n\r\nAdd the eggs; fry until the whites have started to set. Use a spatula to break the yolks and spread gently. Before the eggs are fully cooked, squish the scallion pancake on top of the eggs so it sticks to them as they finish cooking. \r\n\r\nRemove from heat, fill with toppings of choice (cheese, avocado, sauces, chili crisp is a good move). Roll it up and slice it in half and GO TO TOWN! These are amazing.', '1 scallion pancake\r\na spritz of avocado oil, or a small pat of butter\r\n1 handful of spinach\r\n1–2 eggs\r\nsalt and pepper\r\nhalf an avocado\r\nchili crisp to taste', 0, 10, 558, 'Total Fat 28.8g\r\nCholesterol 186mg\r\nSodium 1838.3mg\r\nTotal Carbohydrate 57.8g\r\nDietary Fibre 10g\r\nSugars 4.5g\r\nProtein 17g\r\nVitamin A 155.1µg\r\nVitamin C 10.2mg\r\nIron 2.1mg\r\nPotassium 591.5mg\r\nPhosphorus 143.1mg', '10'),
(24, 'Crispy-Chicken-Cutlets-on-Plate.jpg', 'Crispy Chicken Cutlets', 'Hot, crunchy, salty, and begging for some sauce! These Crispy Chicken Cutlets are a dinnertime winner.', 'Prep: Slice each chicken breast into 3 thin pieces – start by cutting the tenderloin piece off the back, then cut the remaining chicken breast in half horizontally. Season the chicken with salt and pepper. \r\n\r\nThree Bowls: Use a wide, shallow bowl for each of the following: 1) flour. 2) eggs + hot sauce. 3) panko. Season the flour and panko bowls with 1/2 teaspoon salt.\r\n\r\nDip and Coat: Dip the chicken into the flour; shake off excess. Dip into the egg mixture; let the excess drip off. Press into the panko, turn it a few times, pressing gently, until fully coated. Transfer to a plate. Repeat for all pieces. Spray each piece with avocado oil spray so it’ll get nice and evenly golden.\r\n\r\nCook It Up: Air fry in a single layer at 375 for 6 minutes, then 400 degrees for 2-3 minutes for extra browning. You want an internal temp of at least 165 degrees. I do it in two batches. \r\n\r\nYou’re done! Hot, crunchy, salty, juicy, thin and delicious. Perfect dipped or brushed with just about any sauce.', '453 g. boneless skinless chicken breasts\r\n1 teaspoon salt + freshly ground pepper to taste\r\n63 grams flour\r\n2 eggs, beaten\r\n1 tablespoon hot sauce like Frank’s red hot (a few shakes, optional)\r\n216 grams panko breadcrumbs\r\navocado oil spray\r\nmore salt', 15, 10, 477, 'Total Fat 11.9g\r\nCholesterol 175.7mg\r\nSodium 1135.1mg\r\nTotal Carbohydrate 51.7g\r\nDietary Fibre 3g\r\nSugars 4g\r\nProtein 37.5g\r\nVitamin A 56.6µg\r\nVitamin C 2.7mg\r\nIron 4.3mg\r\nPotassium 551.6mg\r\nPhosphorus 399mg', '10'),
(25, 'Buffalo-Chicken-Burgers-3.jpg', 'Buffalo Chicken Burger', 'Easy and amazing buffalo chicken burgers! Piled high with crisp lettuce, a whipped feta spread, sitting atop of toasted brioche bun.', 'Make the Whipped Feta: Blend all the ingredients together in a small food processor until it incorporates into a thick and spreadable texture with flecks of feta.\r\n\r\nMix the Burgers: In a medium bowl, mix the burger ingredients together. Form into 4-5 patties.\r\nCook the Burgers: Heat a small bit of oil in a large nonstick skillet over medium high heat. Add burgers and cook for 3-4 minutes per side, until golden brown and the inside of the burgers is fully cooked (165 degrees).\r\n\r\nBrush with Butter: As the burgers are finishing cooking, brush the outside with the melted butter and hot sauce mixture.\r\n\r\nFinish: Arrange your burgers on buns with lettuce and a shmear of the whipped feta.', '448 g. ground chicken\r\n54 grams panko breadcrumbs\r\n79 ml hot sauce\r\n1/2 teaspoon garlic powder\r\n1/2 teaspoon salt\r\n170 grams feta cheese (blocks or crumbles)\r\n113 grams cream cheese\r\n71 grams Greek yogurt\r\n2 tablespoon olive oil\r\n2 tablespoon water\r\n2 tablespoon melted butter + 2 tablespoon hot sauce for brushing\r\nBuns\r\nLettuce', 15, 10, 570, 'Total Fat 33.8g\r\nCholesterol 167.8mg\r\nSodium 1876.9mg\r\nTotal Carbohydrate 34.1g\r\nDietary Fibre 1.9g\r\nSugars 7.9g\r\nProtein 33g\r\nVitamin A 288.9µg\r\nVitamin C 0.7mg\r\nIron 3.1mg\r\nPotassium 811.3mg\r\nPhosphorus 445.3mg', '15'),
(26, 'Crockpot-Chicken-Bowls.jpg', 'Crockpot Chicken Bowl', 'Saucy shredded chicken, yellow rice, pickled onions, greens, and cilantro pesto on top. It’s a flavour and colour delight!', 'For the Crockpot Chicken: Put the chicken in a slow cooker and cover with sauce; cook for 2 1/2 – 3 hours on high. Shred chicken directly in the slow cooker. Add the corn starch slurry to thicken the sauce. Season to taste.\r\n\r\nFor the Rice: Rinse the rice in a fine mesh colander. Add the rice, water, butter, salt, chili powder, and turmeric to a small saucepan. Bring to a boil. Cover, reduce heat, and cook for 15 minutes. Remove the cover, fluff it up, and season to taste.\r\n\r\nFor the Cilantro Pesto: Blend everything up in a small chopper or food processor until smooth-ish (I leave some texture in mine)! It’ll be a very thick sauce, which I love – you can add water as desired to thin it out.\r\n\r\nBuild Your Bowls: I love the combination of the yellow rice with a scoop of chicken, some salad greens, pickled onions, and a dollop of that cilantro pesto on top! \r\n', '454 g. chicken breasts\r\n237 ml of a simmer sauce or enchilada sauce that you like\r\n1 tablespoon corn starch whisked into 1–2 tablespoons water\r\nextra seasoning to taste – I often add a few shakes of chipotle powder, salt, and pepper\r\n186 grams white rice\r\n356 ml water\r\n1 tablespoon butter\r\n1/2 teaspoon salt\r\n1/2 teaspoon chili powder\r\n1/8 teaspoon turmeric\r\n24 grams cilantro leaves\r\n 1/2 jalapeño, ribs and seeds removed\r\n1 clove garlic\r\njuice and zest of 1 lime\r\n59 ml olive oil or avocado oil\r\n6', 20, 120, 367, 'Total Fat 22g\r\nCholesterol 72.3mg\r\nSodium 532.1mg\r\nTotal Carbohydrate 19g\r\nDietary Fibre 1.9g\r\nSugars 2g\r\nProtein 24.3g\r\nVitamin A 68µg\r\nVitamin C 6.6mg\r\nIron 1.4mg\r\nPotassium 533.7mg\r\nPhosphorus 278.8mg', '14'),
(27, 'Sweet-Potato-Soup-1.jpg', 'Sweet Potato Soup with Cauliflower', 'This sweet potato soup is luscious, silky, and seriously good! Sweet potatoes and red onion get roasted and blended to perfection, and topped with crispy bits of cauliflower.', 'Preheat Oven: Preheat the oven to 425 degrees.\r\n\r\nRoast Soup Vegetables: Place sweet potatoes and onions on a sheet pan; drizzle with oil, sprinkle with salt. Roast in the oven for 30-45 minutes, until golden brown and softened.\r\n\r\nRoast Cauliflower: At the same time, place the cauliflower florets on a second sheet pan, toss with 1 tablespoon oil and 1 teaspoon salt. Roast in the oven for 30 minutes (I just tuck it underneath the sweet potatoes and onions).\r\n\r\nCrumble Cauliflower: Break up the cauliflower into smaller bits using the back of a wooden spoon or spatula. Return to the oven for 10-15 minutes to get extra browned.\r\n\r\nBlend Soup: Allow the sweet potatoes and onions to cool slightly. Working in two batches, transfer the sweet potatoes and onions to a blender. Add broth, ginger, and macadamia nuts; puree until very smooth.\r\n\r\nServe: Taste and adjust for salt. Serve the soup topped with the roasted cauliflower bits, a sprinkle of chives, a drizzle of oil, and a squeeze of lemon ', '1 red onion, cut into large chunks or slices\r\n3 sweet potatoes, cut into chunks (about 6 cup)\r\n2 tablespoon avocado oil or olive oil\r\n1 teaspoon coarse kosher salt\r\n1.4 litre vegetable broth\r\n1/2 inch knob of fresh ginger\r\n67 grams macadamia nuts or cashews\r\n428–642 grams cauliflower florets \r\na small bundle of chives, \r\nand chopped lemon wedges\r\na drizzle of infused oil', 20, 45, 241, 'Total Fat 15.8g\r\nCholesterol 0mg\r\nSodium 821.3mg\r\nTotal Carbohydrate 24.2g\r\nDietary Fibre 5.1g\r\nSugars 7.8g\r\nProtein 3.9g\r\nVitamin A 472.6µg\r\nVitamin C 46.8mg\r\nIron 1.2mg\r\nPotassium 562.4mg\r\nPhosphorus 97.3mg', '10'),
(28, 'Chicken-Tinga-Tacos-6.jpg', 'Chicken Tinga Tacos', 'These Chicken Tinga Tacos are THE BEST! Saucy, spicy, real food perfection.', 'Sauce: Heat a large skillet over medium. Once warm, add the oil and onion. Sauté for 4 minutes or until tender, stirring occasionally. Add in the garlic and cook for 30 seconds more. Stir in the chipotles, oregano, and cumin, and toast for 1 minute. Add in the tomatoes, stock, and salt. Bring to a simmer, and cook for 7 minutes.\r\n\r\nBlend: Place the tomato mixture in a high-powered or regular blender, and blend until smooth.\r\n\r\nChicken: Return the blended sauce to the pan over low heat. Add the chicken, and cook for 5 minutes. Taste and add more salt if necessary.\r\n\r\nServing: Prepare the garnishes. To assemble, top the tortillas with the chicken and garnish with the avocado slices, cilantro, red onion, and cotija. Serve with a lime wedge for squeezing.', '1 tablespoon olive oil\r\n1 cup roughly chopped sweet onion\r\n2 cloves garlic, minced\r\n1–2 chipotle peppers in adobo sauce, chopped\r\n1 teaspoon dried oregano\r\n1/2 teaspoon ground cumin\r\n178 ml canned crushed fire-roasted tomatoes\r\n59 ml chicken stock\r\n1/2 teaspoon kosher salt\r\n420 grams shredded cooked chicken (rotisserie chicken works!)\r\n10 (6-inch) corn tortillas\r\n2 ripe avocados, sliced\r\n4 grams chopped fresh cilantro\r\n80 grams diced red onion\r\n30 grams crumbled cotija\r\n1 lime, cut into wedges', 10, 10, 216, 'Total Fat 8.1g\r\nCholesterol 57.9mg\r\nSodium 176.8mg\r\nTotal Carbohydrate 15.9g\r\nDietary Fibre 3.8g\r\nSugars 1.5g\r\nProtein 19.6g\r\nVitamin A 17.5µg\r\nVitamin C 5.4mg\r\nIron 1mg\r\nPotassium 1589.5mg\r\nPhosphorus 267.3mg', '6'),
(29, 'bbq-chicken-pizza.jpg', 'BBQ Chicken Pizza', 'This light BBQ chicken pizza has just 200 calories per slice thanks to a cauliflower pizza crust. Healthy, colourful, and full of BBQ flavour!', 'Chop the cauliflower into medium pieces. Heat a large skillet over high heat. Add the oil and heat until shiny. Place the cauliflower in the hot oil, stir to cover with oil, and place a lid on the skillet. Check skillet every few minutes to keep cauliflower from burning, but let it get a nice browned roasted outside. Continue to let cauliflower cook, covered, for about 10-15 minutes, or until tender-crisp. Let cool for a few minutes.\r\n\r\nPreheat the oven to 450. In a blender or large food processor, place eggs, cornmeal, and seasoning. Place cauliflower in blender and puree until you have a semi-smooth, thick batter. Cover a pizza pan with parchment paper and pour batter into the centre of the paper. Spread until you have about a 1/2 inch thick crust or thinner if desired.\r\n\r\nBake the crust for about 20 minutes, checking occasionally to keep from burning. I liked the crispy edges, so I recommend letting it brown a little bit more than a normal crust.\r\n\r\nWhile crust is cooking, coat chic', '1 head cauliflower\r\n2 tablespoons olive oil\r\nsalt and pepper\r\n1/2 cup cornmeal\r\n2 eggs\r\n2 teaspoons Italian seasoning\r\n1/2 cup barbecue sauce\r\n1 cup cooked, shredded chicken\r\n3/4 cup shredded Mozzarella cheese\r\nthinly sliced red onions\r\nfresh cilantro', 30, 40, 201, 'Total Fat 8.9g\r\nCholesterol 67mg\r\nSodium 660.6mg\r\nTotal Carbohydrate 19.1g\r\nDietary Fibre 2.4g\r\nSugars 8.5g\r\nProtein 12.1g\r\nVitamin A 46.7µg\r\nVitamin C 35.7mg\r\nIron 1.4mg\r\nPotassium 343.2mg\r\nPhosphorus 192.4mg', '20'),
(30, 'pancakes-61.jpg', 'Cinnamon Whole Grain Pancake', 'These Cinnamon Whole Grain Power Pancakes are so simple to make and will keep you full all morning.', 'Place all ingredients in a blender and blend for 30 seconds until the batter is mostly smooth. Preheat a griddle to medium high heat.\r\n\r\nPour about 1/4 cup pancake batter onto the hot griddle and cook for about three minutes or until bubbles form on top. Flip to the other side and cook for another 1-2 minutes. Transfer to a plate and serve topped with fruit, almond butter, chia seeds, and of course, maple syrup.', '1/2 cup milk\r\n1 cup cottage cheese\r\n2 whole eggs and 2 egg whites\r\n1/2 cup wheat flour\r\n1/2 cup rolled oats\r\n1/2 tsp baking soda\r\n1/2 teaspoon cinnamon\r\n1/4 teaspoon vanilla', 10, 10, 66, 'Total Fat 1.2g\r\nCholesterol 32.3mg\r\nSodium 154.5mg\r\nTotal Carbohydrate 7.5g\r\nDietary Fibre 1g\r\nSugars 1.1g\r\nProtein 5.5g\r\nVitamin A 16.8µg\r\nVitamin C 0mg\r\nIron 0.5mg\r\nPotassium 83.2mg\r\nPhosphorus 130.1mg', '16'),
(31, 'Coconut-Curry-Salmon.jpg', 'Coconut Curry Salmon', 'Coconut Curry Salmon! Broiled salmon with a salty-sweet spice rub, creamy coconut curry sauce, and steamy rice to soak it all up.', 'Get the oven ready: Preheat the oven to 475 degrees. Line a baking sheet with foil. Place one of the oven racks close-ish to the top, about 6 inches or so.\r\n\r\nMake your rice: Cook rice according to package instructions.\r\n\r\nSalmon: Mix the spices and the olive oil to make a paste. Place the salmon skin side down on the baking sheet. Rub the paste liberally over the top part of the salmon. Bake for 6-12 minutes (depends on salmon thickness and desired doneness – I usually opt for 8-10 minutes). See notes and FAQs for potential broiling issues and alternative methods.\r\n\r\nCoconut Curry Sauce: Heat the olive oil over medium heat. Add garlic, ginger, and lemongrass; sauté for 5 minutes. Add brown sugar and curry paste; sauté for 3 minutes. Add coconut milk. Season with fish sauce, lime juice, and lime zest to taste. Add spinach; stir into the sauce until wilted.\r\n\r\nServe: Place salmon over rice. Cover with sauce, lime juice, and fresh herbs.', '1 lbs. salmon\r\n2 tablespoon brown sugar\r\n1 teaspoon curry powder\r\n2 teaspoon onion powder\r\n1 teaspoon garlic powder\r\n 1/2 teaspoon kosher salt\r\n2 tablespoon olive oil\r\n\r\n1 tablespoon olive oil\r\n2 cloves garlic (minced)\r\n1 small knob of ginger (minced)\r\n1 tablespoon of lemongrass paste\r\n1 tablespoon brown sugar\r\n1 tablespoon red curry paste\r\n1 can coconut milk\r\n2 tablespoon fish sauce or soy sauce\r\nlots of lime juice and zest\r\n90 grams fresh spinach, chopped\r\n\r\ncilantro, basil, mint, or other fre', 10, 20, 476, 'Total Fat 30g\r\nCholesterol 86.9mg\r\nSodium 1207.4mg\r\nTotal Carbohydrate 10.7g\r\nDietary Fiber 1.2g\r\nSugars 5.3g\r\nProtein 41.1g\r\nVitamin A 227.1µg\r\nVitamin C 11mg\r\nIron 4.2mg\r\nPotassium 982.6mg\r\nPhosphorus 530.3mg', '25'),
(32, 'Instant-Pot-Red-Curry-Lentils.jpg', 'Instant Pot Curry Lentils', 'Guess what’s for dinner? Creamy, spicy, delicious red curry lentils, made in the Instant Pot.\r\n\r\nIt’s almost embarrassingly easy: lentils, cooked with a can of tomato sauce, some warm spices, and the essential onion-garlic-ginger trifecta, and made creamy with a little bit of coconut milk and/or butter and/or ghee.\r\n\r\nScoop it over a pile of steamy rice and throw some fresh greens on there and you’ve got a serious comfort food situation that is hard to quit.\r\n\r\nIt’s amazing for dinner and more amazing for leftovers.', 'Place all ingredients in the Instant Pot. Cook on high pressure for 15 minutes. Natural pressure release (meaning just let it sit) for 10 more minutes.\r\n\r\nStir in the coconut milk and butter. Taste and adjust seasonings.\r\n\r\nServe with rice, top with cilantro, and be amazed at the yumminess of the humble little lentil.', '1 1/2 cups brown lentils\r\n1/2 large onion, diced\r\n2 tablespoons red curry paste\r\n1 tablespoon sugar (optional)\r\n1/2 tablespoon garam masala\r\n1 teaspoon curry powder\r\n1/2 teaspoon turmeric\r\n1 teaspoon garlic, minced\r\n1 teaspoon ginger, minced\r\na few good shakes of cayenne pepper\r\n474 ml water\r\n1 14-ounce can tomato sauce\r\n1 teaspoon coarse salt\r\n178 ml coconut milk\r\n2 tablespoon butter or ghee (optional)\r\ncilantro for garnishing\r\nrice for serving', 10, 30, 268, 'Total Fat 6.9g\r\nCholesterol 0mg\r\nSodium 339.2mg\r\nTotal Carbohydrate 37.3g\r\nDietary Fibre 16.5g\r\nSugars 4.3g\r\nProtein 14.7g\r\nVitamin A 246.6µg\r\nVitamin C 6.8mg\r\nIron 5.6mg\r\nPotassium 654.1mg\r\nPhosphorus 53.2mg', '5'),
(33, 'Spicy-Shrimp-with-Cauli-Mash-and-Kale.jpg', 'Shrimp with Cauliflower Mash', 'This spicy / creamy / green combination is pretty much life goals. \r\n\r\nA pile of creamy cauliflower mash sets the stage and then gets tucked in by those smoky greens before finally getting piled high with a mound of aggressively seasoned, salty, juicy shrimp.\r\n\r\nDo you even hear me? Leave work immediately. Pack the kids in the car. Go to the grocery store. Make this your life’s mission.\r\n\r\nIt is peak cozy, nutritious, and cool-weather wonderful.', 'Cauliflower Mash: Heat the olive oil in a large soup pot. Add the cauliflower and garlic. Saute for a minute or two, until the garlic is fragrant. Add the milk and 2 cups broth. Simmer for 10 minutes or until soft. Add the white beans and mash roughly with the back of a large wooden spoon. It will be soupy – that’s okay. Stir in the cornmeal and things will start to thicken a bit. Adjust the consistency by adding in the last cup of broth as needed. Stir in the cheese and season to taste.\r\n\r\nKale: Heat the bacon fat in a non-stick skillet over medium low heat. Add the greens and garlic and saute until softened. For the palettes, I added a little water at the end to sort of steam them to finish them off. Remove kale and wipe out pan with a paper towel.\r\n\r\nShrimp: In the same skillet, add the oil over medium heat. Pat the shrimp dry. Add to the pan and sprinkle with seasonings to taste. Cook for just a few minutes and then add a quick splash of water or broth to the pan (about 2 tablespoo', '2 tablespoon olive oil\r\n1 head cauliflower, cut into small florets (about 6 cup)\r\n3 cloves garlic, minced\r\n237 ml milk\r\n711 ml vegetable or chicken broth\r\none 14-ounce can white beans, rinsed and drained\r\n61 grams cornmeal\r\n1/2 cup shredded cheese, like sharp cheddar or Havarti\r\n1 teaspoon salt\r\nFor the Kale\r\n1 tablespoon bacon fat (or olive oil)\r\n48 grams palettes or chopped kale\r\n3 cloves garlic, minced\r\nFor the Shrimp\r\n1 tablespoon olive oil\r\n680 g. shrimp (enough for 4 people)\r\na few good sh', 15, 30, 537, 'Total Fat 21.8g\r\nCholesterol 290.7mg\r\nSodium 980mg\r\nTotal Carbohydrate 38g\r\nDietary Fibre 7.7g\r\nSugars 6.1g\r\nProtein 50.4g\r\nVitamin A 153.9µg\r\nVitamin C 48.3mg\r\nIron 4.4mg\r\nPotassium 996.1mg\r\nPhosphorus 757.1mg', '19'),
(34, 'Carrot-Cake-Coffee-Cake-1.jpg', 'Carrot Cake Coffee Cake', 'I know it might be confusing – carrot cake, which is like vegetables meets cake, but also still cake, combined with coffee cake, which is also cake but the kind you can eat for breakfast and it will still be totally appropriate and, obviously, cakey?\r\nBut no need to be confused.\r\nIf you like a carrot cake that tastes just slightly cinnamony and teeters perfectly between springy and dense…\r\nAnd you like a coffee cake that is loaded with a mega amount of streusel topping…\r\nAnd you like cakes that look beautiful and taste even more beautiful with a proper shmear of honey butter…\r\nHELLO! Meet your one true match. This one is for you.', 'Prep: Preheat the oven to 350 degrees. Peel and grate the carrots (I use my food processor). You should have about 1 1/2 cups.\r\n\r\nCake Batter: Whisk sugar, melted butter, and eggs. Stir in carrots. Add flour, baking soda, and cinnamon. Mix until just combined. The batter will be very thick.\r\n\r\nStreusel: Mix ingredients for the streusel until you get a texture that looks like pebbles (sometimes I use my hands to mix it all together).\r\n\r\nBake: Spread batter into a greased 9-inch round baking pan (see FAQs). Sprinkle with streusel. Bake for 30 minutes until the centre is just set. Top with honey butter if you’re extra (you are).', '2 large carrots\r\n150 grams granulated sugar\r\n114 grams butter, melted\r\n2 eggs\r\n125 grams flour\r\n1 teaspoon baking soda\r\n1 teaspoon cinnamon\r\npinch of salt\r\n4 tablespoon butter, melted\r\n63 grams flour\r\n100 grams brown sugar\r\n1 teaspoon cinnamon\r\npinch of salt', 20, 35, 297, 'Total Fat 15g\r\nCholesterol 73.8mg\r\nSodium 269.2mg\r\nTotal Carbohydrate 38.1g\r\nDietary Fibre 1.1g\r\nSugars 22.7g\r\nProtein 3.5g\r\nVitamin A 234.4µg\r\nVitamin C 0.7mg\r\nIron 1.2mg\r\nPotassium 89.2mg\r\nPhosphorus 49mg', '17'),
(35, 'Lemon-Pie.jpeg', 'Creamy Lemon Pie', 'Calling all lemon lovers! And all non-lemon lovers! It really doesn’t matter what your thoughts are on lemon desserts, because this is a dessert in a league entirely of its own.\r\n\r\nI will be the first to admit that I typically don’t like lemon-y sweets. Lemon bars, lemon pies, lemon curd? None of it really appeals to me. However, I absolutely love this creamy lemon pie. That’s my testimonial for all the non-lemon lovers. Just give it a try – I dare you not to like it.\r\n\r\nAnd why wouldn’t you try it, since you probably have all the ingredients in your kitchen right now?', 'Preheat the oven to 375. Combine first four ingredients and press into a pie pan with the back of a spoon. Bake for 8 minutes and cool for at least 15 minutes.\r\n\r\nPour sweetened condensed milk into a large mixing bowl. Squeeze the juice from the lemons and lime into the bowl and whisk together. Fold in the whipped topping and stir until combined.\r\n\r\nPour lemon mixture into piecrust. Freeze overnight. Remove from freezer about 10 minutes before serving.\r\n\r\nWhisk a few tablespoons of blackberry jam with a tablespoon of water until you get a good consistency. Pour over slices and enjoy!\r\n', '1 pkg. graham crackers, crushed (about 9 crackers)\r\n1/3 cup sugar\r\n6 tbs. butter, melted\r\n1 tsp. cinnamon\r\n14 oz can sweetened condensed milk\r\n8 oz. whipped topping (aka Cool Whip)\r\n3 lemons\r\n1 lime\r\nboysenberry jam', 15, 10, 442, 'Total Fat 16.7g\r\nCholesterol 47.1mg\r\nSodium 169mg\r\nTotal Carbohydrate 69.3g\r\n6%Dietary Fibre 1.7g\r\nSugars 55.6g\r\nProtein 7.1g\r\nVitamin A 127.3µg\r\nVitamin C 16.6mg\r\nIron 1mg\r\nPotassium 335.3mg\r\nPhosphorus 214.3mg', '8'),
(36, 'Healthy-Couscous-Summer-Salad.jpg', 'Couscous Salad', 'Couscous Summer Salad, because juicy nectarines and avocados and mint and cucumbers and chickpeas and cherries and couscous and fresh sweet corn definitely go together, right?', 'Combine couscous, cherries, cumin, coriander, and salt and pepper in a bowl. Pour warm broth over everything and let stand until the couscous is cooked, about 5 minutes. Let it cool.\r\n\r\nToss everything together and season to taste!', '1 cup couscous (uncooked)\r\n1/2 cup dried cherries\r\n1 teaspoon ground cumin\r\n1 teaspoon ground coriander\r\n1 1/4 cups chicken or veggie broth, warm\r\nsalt and pepper\r\n1 can chickpeas, rinsed and drained\r\n2 pieces of fresh sweet corn, kernels cut off the cob\r\n2 nectarines or peaches, diced\r\n1 cucumber, diced\r\n1 avocado, cut into chunks\r\n1/4 red onion, finely diced\r\n1/2 cup pepitas, sunflower seeds, or something crunchy\r\n2 cups arugula or spinach\r\nparsley / mint / basil / any herbs, really\r\nlemon jui', 15, 5, 465, 'Total Fat 20.3g\r\nCholesterol 0mg\r\nSodium 230.9mg\r\nTotal Carbohydrate 63.3g\r\nDietary Fibre 8.9g\r\nSugars 20g\r\nProtein 13.1g\r\nVitamin A 84.3µg\r\nVitamin C 18.9mg\r\nIron 3mg\r\nPotassium 696.1mg\r\nPhosphorus 298.5mg', '11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(255) NOT NULL,
  `userName` varchar(20) NOT NULL,
  `userEmail` varchar(30) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userType` varchar(5) NOT NULL DEFAULT 'user',
  `number` varchar(15) NOT NULL,
  `userAddress` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userName`, `userEmail`, `userPassword`, `userType`, `number`, `userAddress`) VALUES
(17, 'Hassan', 'hassan@gmail.com', '$2y$10$Y61mRLeczjXFFmtUqE7mlewbwTbQHFtyF/sR.KmMqiiktP7s8fMb2', 'admin', '', ''),
(18, 'John', 'john@gmail.com', '$2y$10$oPtKFRB9F9S95m8t43QzcOZPxPihwUWIKDYfCUB/HsDgdt.gSE9Ia', 'user', '', ''),
(19, 'Sam', 'sam@gmail.com', '$2y$10$aT4RGz0Q7IUjZyM3xbs4T.Zd1CUqsBm2tuJoXF794xGZLz3m5i/J.', 'user', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookmark`
--
ALTER TABLE `bookmark`
  ADD PRIMARY KEY (`bookmarkId`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentId`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredientId`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`messageId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipeId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookmark`
--
ALTER TABLE `bookmark`
  MODIFY `bookmarkId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredientId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `messageId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipeId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
