$platoonsInGroup = [1, 2, 3, 4, 5];
$cycles = []; // Stores elements temporarily
$seenElements = []; // Tracks elements that have appeared at position 0

while (count($seenElements) < count($platoonsInGroup)) {
    $tempArray = $platoonsInGroup; // Clone the array for shuffling

    while (!empty($tempArray)) {
        shuffle($tempArray); // Shuffle the array
        $firstElement = array_shift($tempArray); // Remove the first element

        $cycles[] = $firstElement; // Store in cycles

        // Mark element as seen
        $seenElements[$firstElement] = true;

        // Consume $cycles
        echo "Consuming: " . implode(", ", $cycles) . "\n";

        // Empty cycles for next loop
        $cycles = [];
    }

    echo "Cycle completed. Restarting...\n";
    $seenElements = []; // Reset tracking after all elements appeared
}
