# Example text
letters= "HelloWorld"

from collections import Counter
import string

def sorted_letters(text):
    # Remove non-alphabetic characters and convert to lowercase
    cleaned_text = ''.join(char.lower() for char in text if char.isalpha())

    # Use Counter to count occurrences of each letter
    letter_counts = Counter(cleaned_text)

    # Order the letters by frequency (most frequent to least frequent)
    sorted_letters = [letter for letter, _ in sorted(letter_counts.items(), key=lambda x: x[1], reverse=True)]

    return sorted_letters


# Get the list of sorted letters
sorted_letter_list = sorted_letters(letters)

# Print the result
print("sorted_letter_list", sorted_letter_list)